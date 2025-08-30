<?php

// TSC TSPL (TSC Printer Language)
class TSPLEncoder extends BaseLabelEncoder
{
    public function initialize(): self
    {
        $this->commands[] = 'SIZE ' . ($this->labelWidth / $this->dpi) . ' mm, ' . ($this->labelHeight / $this->dpi) . ' mm';
        $this->commands[] = 'DIRECTION 1';
        $this->commands[] = 'REFERENCE 0,0';
        $this->commands[] = 'SPEED ' . $this->mapSpeed($this->speed);
        $this->commands[] = 'DENSITY ' . $this->mapDensity($this->density);
        $this->commands[] = 'CLS';
        return $this;
    }

    public function finalize(): self
    {
        $this->commands[] = 'PRINT ' . $this->copies;
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
            'light' => '5',
            'medium_light' => '8',
            'medium' => '10',
            'dark' => '15'
        };
    }

    public function addText(string $text, int $y, int $xMul = 1, int $yMul = 1, string $align = 'left'): self
    {
        $x = match ($align) {
            'center' => $this->centerX(strlen($text) * 12 * $xMul),
            'right' => $this->rightX(strlen($text) * 12 * $xMul),
            default => 0
        };
        $this->commands[] = "TEXT {$x},{$y},\"1\",0,{$xMul},{$yMul},\"{$text}\"";
        return $this;
    }

    public function addBarcode(string $data, int $y, int $height = 50, string $align = 'left'): self
    {
        $x = match ($align) {
            'center' => $this->centerX(strlen($data) * 12),
            'right' => $this->rightX(strlen($data) * 12),
            default => 0
        };
        $this->commands[] = "BARCODE {$x},{$y},\"128\",{$height},1,0,2,2,\"{$data}\"";
        return $this;
    }
}
