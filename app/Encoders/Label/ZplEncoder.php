<?php

namespace App\Encoders\Label;

class ZPLEncoder extends BaseEncoder
{
    protected function dpi(): int
    {
        return 203;
    }

    protected function buildHeader(): void
    {
        $heightDots = $this->mmToDot($this->heightMm);
        $widthDots = $this->mmToDot($this->widthMm);

        $this->commands[] = "^XA";
        $this->commands[] = "^MNY";
        $this->commands[] = "^LH0,0";
        $this->commands[] = "^LL{$heightDots}";
        $this->commands[] = "^PW{$widthDots}";
    }

    protected function buildFooter(): void
    {
        $this->commands[] = "^PQ{$this->copies}";
        $this->commands[] = "^XZ";
    }

    protected function textCommand(int $x, int $y, string $text, int $fontSize): string
    {
        // ZPL needs width and height parameters
        $fontDimensions = $this->getZplFontDimensions($fontSize);
        return "^FO{$x},{$y}^A0N,{$fontDimensions[0]},{$fontDimensions[1]}^FD{$text}^FS";
    }

    protected function barcodeCommand(int $x, int $y, string $data, int $height): string
    {
        return "^FO{$x},{$y}^BY2^BCN,{$height},Y,N^FD{$data}^FS";
    }

    protected function mapFontSize(string $size): int
    {
        // ZPL also uses 1-5 for consistency, but maps to different dimensions
        return self::FONT_SIZES[$size];
    }

    private function getZplFontDimensions(int $fontSize): array
    {
        return match ($fontSize) {
            1 => [12, 20],  // xs: 12x20
            2 => [16, 24],  // s: 16x24  
            3 => [24, 32],  // m: 24x32
            4 => [32, 48],  // l: 32x48
            5 => [48, 72],  // xl: 48x72
            default => [24, 32] // default to medium
        };
    }
}
