<?php

// Eltron Programming Language (EPL)
class EPLEncoder extends BaseLabelEncoder
{
    public function initialize(): self
    {
        $this->commands[] = 'N';
        $this->commands[] = 'q' . $this->labelWidth;
        $this->commands[] = 'Q' . $this->labelHeight . ',0';
        $this->commands[] = 'S' . $this->mapSpeed($this->speed);
        $this->commands[] = 'D' . $this->mapDensity($this->density);
        return $this;
    }

    public function finalize(): self
    {
        $this->commands[] = 'P' . $this->copies;
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
            'light' => '0',
            'medium_light' => '5',
            'medium' => '10',
            'dark' => '15'
        };
    }

    public function addText(string $text, int $y, int $hMult = 1, int $vMult = 1, string $align = 'left'): self
    {
        $x = match ($align) {
            'center' => $this->centerX(strlen($text) * 8 * $hMult),
            'right' => $this->rightX(strlen($text) * 8 * $hMult),
            default => 0
        };
        $this->commands[] = "A{$x},{$y},0,1,{$hMult},{$vMult},N,\"{$text}\"";
        return $this;
    }

    public function addBarcode(string $data, int $y, int $height = 50, string $align = 'left'): self
    {
        $x = match ($align) {
            'center' => $this->centerX(strlen($data) * 12),
            'right' => $this->rightX(strlen($data) * 12),
            default => 0
        };
        $this->commands[] = "B{$x},{$y},0,1C,2,6,{$height},N,\"{$data}\"";
        return $this;
    }
}
