<?php

namespace App\Encoders;

use App\Enums\PrintAlign;
use App\Enums\PrintDensity;
use App\Enums\PrintSpeed;
use App\Enums\PrintSize;

class TsplEncoder extends BaseLabelEncoder
{
    private $densityMap = [
        'light' => '6',
        'medium_light' => '8',
        'medium' => '10',
        'medium_dark' => '12',
        'dark' => '15'
    ];

    private $speedMap = [
        'slow' => '2',
        'normal' => '4',
        'fast' => '6'
    ];

    private $sizeMap = [
        'small' => [1, 1],
        'medium' => [2, 2],
        'large' => [3, 3]
    ];

    public function initialize(PrintDensity $density = PrintDensity::MEDIUM, PrintSpeed $speed = PrintSpeed::NORMAL): void
    {
        $widthInches = $this->labelWidth / 203;
        $heightInches = $this->labelHeight / 203;

        $this->buffer = "SIZE {$widthInches},{$heightInches}\n";
        $this->buffer .= "GAP 0.1,0\n";
        $this->buffer .= "DENSITY " . $this->densityMap[$density->value] . "\n";
        $this->buffer .= "SPEED " . $this->speedMap[$speed->value] . "\n";
        $this->buffer .= "DIRECTION 1\n";
        $this->buffer .= "CLS\n";
    }

    public function addText(int $y, string $text, PrintSize $size = PrintSize::MEDIUM, PrintAlign $alignment = PrintAlign::LEFT): void
    {
        $scale = $this->sizeMap[$size->value];
        $textWidth = strlen($text) * (12 * $scale[0]);

        $x = 0;
        if ($alignment == PrintAlign::CENTER) {
            $x = ($this->labelWidth - $textWidth) / 2;
        } elseif ($alignment == PrintAlign::RIGHT) {
            $x = $this->labelWidth - $textWidth;
        }

        $this->buffer .= "TEXT {$x},{$y},\"0\",0,{$scale[0]},{$scale[1]},\"{$text}\"\n";
    }

    public function addBarcode(int $y, string $data, int $height = 50): void
    {
        $barcodeWidth = strlen($data) * 20;
        $x = ($this->labelWidth - $barcodeWidth) / 2;

        $this->buffer .= "BARCODE {$x},{$y},\"128\",{$height},1,0,2,2,\"{$data}\"\n";
    }

    public function print(int $copies = 1): void
    {
        $this->buffer .= "PRINT {$copies}\n";
    }
}
