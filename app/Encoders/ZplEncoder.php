<?php

// Zebra Programming Language (ZPL II)
class ZPLEncoder extends BaseLabelEncoder
{
    public function initialize(): self
    {
        $this->commands[] = '^XA';
        $this->commands[] = '^LH0,0';
        $this->commands[] = '^LL' . $this->labelHeight;
        $this->commands[] = '^PW' . $this->labelWidth;
        $this->commands[] = '^MD' . $this->mapDensity($this->density);
        $this->commands[] = '^PR' . $this->mapSpeed($this->speed);
        return $this;
    }

    public function finalize(): self
    {
        $this->commands[] = '^PQ' . $this->copies;
        $this->commands[] = '^XZ';
        return $this;
    }

    protected function mapSpeed(string $speed): string|int
    {
        return match ($speed) {
            'slow' => '2',
            'normal' => '4',
            'fast' => '6'
        };
    }

    protected function mapDensity(string $density): string|int
    {
        return match ($density) {
            'light' => '0',
            'medium_light' => '5',
            'medium' => '10',
            'dark' => '15'
        };
    }

    public function addText(string $text, int $y, int $size = 30, string $align = 'left'): self
    {
        $x = match ($align) {
            'center' => $this->centerX(strlen($text) * $size / 2),
            'right' => $this->rightX(strlen($text) * $size / 2),
            default => 0
        };
        $this->commands[] = "^FO{$x},{$y}^A0N,{$size},{$size}^FD{$text}^FS";
        return $this;
    }

    public function addBarcode(string $data, int $y, int $height = 100, string $align = 'left'): self
    {
        $x = match ($align) {
            'center' => $this->centerX(strlen($data) * 10),
            'right' => $this->rightX(strlen($data) * 10),
            default => 0
        };
        $this->commands[] = "^FO{$x},{$y}^BY2,3,{$height}^B3N,N,Y,N^FD{$data}^FS";
        return $this;
    }
}
