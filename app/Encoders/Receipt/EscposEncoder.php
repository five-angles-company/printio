<?php

namespace App\Encoders\Receipt;

use Illuminate\Support\Facades\Log;

class EscPosEncoder
{
    private array $commands = [];

    public function initialize(): self
    {
        $this->commands[] = pack('C2', 0x1b, 0x40);
        $this->commands[] = pack('C3', 0x1b, 0x61, 0x01); // ESC ? - Reset printer
        return $this;
    }

    public function image(string $pngBinary, float $width, int $dpi = 203): self
    {
        try {
            $pixels = intval($width);
            // Use GD directly for better performance
            $img = imagecreatefromstring($pngBinary);
            if (!$img) {
                throw new \Exception('Invalid image data');
            }

            // Get current dimensions
            $origWidth = imagesx($img);
            $origHeight = imagesy($img);

            // Calculate new dimensions maintaining aspect ratio
            $newHeight = intval(($pixels / $origWidth) * $origHeight);

            // Create resized grayscale image
            $resized = imagecreatetruecolor($pixels, $newHeight);
            imagecopyresampled($resized, $img, 0, 0, 0, 0, $pixels, $newHeight, $origWidth, $origHeight);
            imagefilter($resized, IMG_FILTER_GRAYSCALE);

            [$width, $height, $bitmap] = $this->convertToBitmapFast($resized, $pixels, $newHeight);

            // Cleanup
            imagedestroy($img);
            imagedestroy($resized);

            $widthBytes = ($width + 7) >> 3; // Faster ceil division by 8

            // Pre-calculate command bytes
            $this->commands[] = pack(
                'C8C*',
                0x1d,
                0x76,
                0x30,
                0x00,
                $widthBytes & 0xff,
                ($widthBytes >> 8) & 0xff,
                $height & 0xff,
                ($height >> 8) & 0xff,
                ...$bitmap
            );
        } catch (\Throwable $e) {
            Log::error('Image encoding error: ' . $e->getMessage());
        }

        return $this;
    }

    public function feed(int $lines = 1): self
    {
        $this->commands[] = pack('C3', 0x1b, 0x64, $lines);
        return $this;
    }

    public function beep(bool $enable = true, int $times = 1): self
    {
        if ($enable) {
            $times = min($times, 3); // max 3 beeps per call
            $this->commands[] = pack('C4', 0x1b, 0x42, 0x05, 0x05);
            if ($times > 1) {
                for ($i = 1; $i < $times; $i++) {
                    $this->commands[] = pack('C4', 0x1b, 0x42, 0x05, 0x05);
                }
            }
        }

        return $this;
    }

    public function cut(bool $enable = true): self
    {
        if ($enable) {
            $this->commands[] = pack('C3', 0x1d, 0x56, 0x00);
        }

        return $this;
    }


    private function convertToBitmapFast($img, int $width, int $height): array
    {
        $widthBytes = ($width + 7) >> 3;
        $bitmap = array_fill(0, $widthBytes * $height, 0);
        $threshold = 128;
        $bitMasks = [0x80, 0x40, 0x20, 0x10, 0x08, 0x04, 0x02, 0x01]; // Pre-calculated bit masks

        for ($y = 0; $y < $height; $y++) {
            $rowOffset = $y * $widthBytes;
            for ($x = 0; $x < $width; $x++) {
                $rgb = imagecolorat($img, $x, $y);
                // Extract red component (grayscale, so R=G=B)
                $gray = ($rgb >> 16) & 0xFF;

                if ($gray < $threshold) {
                    $byteIndex = $rowOffset + ($x >> 3); // x / 8
                    $bitmap[$byteIndex] |= $bitMasks[$x & 7]; // x % 8
                }
            }
        }

        return [$width, $height, $bitmap];
    }

    public function getBuffer(): string
    {
        return implode('', $this->commands);
    }
}
