<?php

// SATO SBPL (SATO Barcode Programming Language)
class SBPLEncoder extends BaseLabelEncoder
{
    public function initialize(): self
    {
        $this->commands[] = chr(27) . 'A';
        $this->commands[] = chr(27) . 'H' . sprintf('%04d', $this->labelHeight);
        $this->commands[] = chr(27) . 'V' . sprintf('%04d', $this->labelWidth);
        $this->commands[] = chr(27) . 'CS' . $this->mapSpeed($this->speed);
        $this->commands[] = chr(27) . '#E' . $this->mapDensity($this->density);
        return $this;
    }

    public function finalize(): self
    {
        $this->commands[] = chr(27) . 'Q' . sprintf('%04d', $this->copies);
        $this->commands[] = chr(27) . 'Z';
        return $this;
    }

    protected function mapSpeed(string $speed): string|int
    {
        return match ($speed) {
            'slow' => '1',
            'normal' => '3',
            'fast' => '5'
        };
    }

    protected function mapDensity(string $density): string|int
    {
        return match ($density) {
            'light' => '1',
            'medium_light' => '2',
            'medium' => '3',
            'dark' => '4'
        };
    }

    public function addText(string $text, int $y, int $size = 12, string $align = 'left'): self
    {
        $x = match ($align) {
            'center' => $this->centerX(strlen($text) * $size),
            'right' => $this->rightX(strlen($text) * $size),
            default => 0
        };
        $this->commands[] = chr(27) . sprintf('V%04d', $y);
        $this->commands[] = chr(27) . sprintf('H%04d', $x);
        $this->commands[] = chr(27) . sprintf('L0106');
        $this->commands[] = $text;
        return $this;
    }

    public function addBarcode(string $data, int $y, int $height = 50, string $align = 'left'): self
    {
        $x = match ($align) {
            'center' => $this->centerX(strlen($data) * 12),
            'right' => $this->rightX(strlen($data) * 12),
            default => 0
        };
        $this->commands[] = chr(27) . sprintf('V%04d', $y);
        $this->commands[] = chr(27) . sprintf('H%04d', $x);
        $this->commands[] = chr(27) . sprintf('BD%02d', $height);
        $this->commands[] = $data;
        return $this;
    }
}
