<?php

namespace App\Encoders\Label;

class TSPLEncoder extends BaseEncoder
{
    protected function dpi(): int
    {
        return 203;
    }

    protected function buildHeader(): void
    {
        $widthInches = $this->widthMm / 25.4;
        $heightInches = $this->heightMm / 25.4;
        $this->commands[] = "SIZE {$widthInches},{$heightInches}";
        $this->commands[] = "CLS";
        $this->commands[] = "REFERENCE 0,0";
        $this->commands[] = "DIRECTION 1";
    }

    protected function buildFooter(): void
    {
        $this->commands[] = "PRINT {$this->copies}";
    }

    protected function textCommand(int $x, int $y, string $text, int $fontSize): string
    {
        // TSPL uses font numbers directly (1-5)
        return "TEXT {$x},{$y},\"{$fontSize}\",0,1,1,\"{$text}\"";
    }

    protected function barcodeCommand(int $x, int $y, string $data, int $height): string
    {
        return "BARCODE {$x},{$y},\"128\",{$height},1,0,2,2,\"{$data}\"";
    }

    protected function mapFontSize(string $size): int
    {
        // TSPL uses font numbers directly
        return self::FONT_SIZES[$size];
    }
}
