<?php

namespace App\Encoders;

use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class EscPosEncoder
{
    private array $commands = [];

    public function initialize(): self
    {
        return $this->command([0x1b, 0x40]); // ESC @
    }

    public function text(string $text): self
    {
        $this->commands[] = $text;
        return $this;
    }

    public function line(string $text = ''): self
    {
        return $this->text($text . "\n");
    }

    public function align(string $position): self
    {
        $map = ['left' => 0, 'center' => 1, 'right' => 2];
        return $this->command([0x1b, 0x61, $map[$position] ?? 0]);
    }

    public function bold(bool $on = true): self
    {
        return $this->command([0x1b, 0x45, $on ? 1 : 0]);
    }

    public function feed(int $lines = 1): self
    {
        return $this->command([0x1b, 0x64, $lines]);
    }

    public function cut(bool $on = true): self
    {
        if (!$on) {
            return $this;
        }
        return $this->command([0x1d, 0x56, 0x00]);
    }

    public function beep(bool $on = true, int $times = 3, int $duration = 6): self
    {
        if (!$on) {
            return $this;
        }
        $n = max(1, min($times, 9));
        $t = max(1, min($duration, 9));
        return $this->command([0x1b, 0x42, $n, $t]);
    }

    private function command(array $bytes): self
    {
        $this->commands[] = pack('C*', ...$bytes);
        return $this;
    }

    public function image(string $data, float $widthMm = 72, int $threshold = 160, int $dpi = 203): self
    {
        try {
            $pixels = intval(round(($widthMm / 25.4) * $dpi));

            $manager = new ImageManager(new GdDriver());

            // Read binary image (JPG/PNG raw bytes)
            $img = $manager->read($data)
                ->resize($pixels, null, function ($c) {
                    $c->aspectRatio();
                    $c->upsize();
                })
                ->greyscale();

            [$width, $height, $bitmap] = $this->convertToBitmap($img, $threshold);

            $widthBytes = (int) ceil($width / 8);

            // ESC/POS raster image command (GS v 0)
            $this->command([
                0x1d,
                0x76,
                0x30,
                0x00,
                $widthBytes & 0xff,
                ($widthBytes >> 8) & 0xff,
                $height & 0xff,
                ($height >> 8) & 0xff,
            ]);

            $this->commands[] = pack('C*', ...$bitmap);
        } catch (\Throwable $e) {
            Log::error('Image encoding error: ' . $e->getMessage());
            $this->line('[IMAGE ERROR]');
        }

        return $this;
    }

    private function convertToBitmap($img, int $threshold): array
    {
        $width = $img->width();
        $height = $img->height();
        $widthBytes = (int) ceil($width / 8);
        $bitmap = [];

        for ($y = 0; $y < $height; $y++) {
            for ($xByte = 0; $xByte < $widthBytes; $xByte++) {
                $byte = 0;
                for ($bit = 0; $bit < 8; $bit++) {
                    $x = $xByte * 8 + $bit;
                    if ($x < $width) {
                        $color = $img->pickColor($x, $y);
                        $gray = $color->red(); // after greyscale, R=G=B
                        if ($gray < $threshold) {
                            $byte |= (0x80 >> $bit);
                        }
                    }
                }
                $bitmap[] = $byte;
            }
        }

        return [$width, $height, $bitmap];
    }


    public function getBuffer(): string
    {
        return implode('', $this->commands);
    }
}
