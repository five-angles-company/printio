<?php

namespace App\Encoders;

use App\Enums\PrintAlign;
use App\Enums\PrintDensity;
use App\Enums\PrintSpeed;
use App\Enums\PrintSize;

class DplEncoder extends BaseLabelEncoder
{
    private $densityMap = [
        'light' => 'L',
        'medium_light' => 'ML',
        'medium' => 'M',
        'medium_dark' => 'MD',
        'dark' => 'D'
    ];

    private $speedMap = [
        'slow' => 'A',
        'normal' => 'B',
        'fast' => 'C'
    ];

    private $sizeMap = [
        'small' => '1',
        'medium' => '2',
        'large' => '3'
    ];

    public function initialize(PrintDensity $density = PrintDensity::MEDIUM, PrintSpeed $speed = PrintSpeed::NORMAL): void
    {
        $this->buffer = "H" . $this->densityMap[$density->value] . "\n";
        $this->buffer .= "S" . $this->speedMap[$speed->value] . "\n";
    }

    public function addText(int $y, string $text, PrintSize $size = PrintSize::MEDIUM, PrintAlign $alignment = PrintAlign::LEFT): void
    {
        $fontSize = $this->sizeMap[$size->value];
        $charWidths = ['1' => 20, '2' => 30, '3' => 50];
        $textWidth = strlen($text) * $charWidths[$fontSize];

        $x = 0;
        if ($alignment == PrintAlign::CENTER) {
            $x = ($this->labelWidth - $textWidth) / 2;
        } elseif ($alignment == PrintAlign::RIGHT) {
            $x = $this->labelWidth - $textWidth;
        }

        $this->buffer .= "{$x},{$y},{$fontSize},1,1,N,\"{$text}\"\n";
    }

    public function addBarcode(int $y, string $data, int $height = 50): void
    {
        $barcodeWidth = strlen($data) * 11;
        $x = ($this->labelWidth - $barcodeWidth) / 2;

        $this->buffer .= "B{$x},{$y},0,1,2,2,{$height},B,\"{$data}\"\n";
    }

    public function print(int $copies = 1): void
    {
        $this->buffer .= "Q{$copies}\nE\n";
    }
}
