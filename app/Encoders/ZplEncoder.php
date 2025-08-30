<?php

namespace App\Encoders;

use App\Enums\PrintAlign;
use App\Enums\PrintDensity;
use App\Enums\PrintSpeed;
use App\Enums\PrintSize;

class ZplEncoder extends BaseLabelEncoder
{
    private $densityMap = [
        'light' => -15,
        'medium_light' => -8,
        'medium' => 0,
        'medium_dark' => 8,
        'dark' => 15
    ];

    private $speedMap = [
        'slow' => 'A',
        'normal' => 'C',
        'fast' => 'E'
    ];

    private $sizeMap = [
        'small' => [20, 20],
        'medium' => [30, 30],
        'large' => [50, 50]
    ];

    public function initialize(PrintDensity $density = PrintDensity::MEDIUM, PrintSpeed $speed = PrintSpeed::NORMAL): void
    {
        $this->buffer = "^XA\n";
        $this->buffer .= "^MD" . $this->densityMap[$density->value] . "\n";
        $this->buffer .= "^PR" . $this->speedMap[$speed->value] . "\n";
    }

    public function addText(int $y, string $text, PrintSize $size = PrintSize::MEDIUM, PrintAlign $alignment = PrintAlign::LEFT): void
    {
        $fontSize = $this->sizeMap[$size->value];
        $textWidth = strlen($text) * ($fontSize[1] * 0.6);

        $x = 0;
        if ($alignment == PrintAlign::CENTER) {
            $x = ($this->labelWidth - $textWidth) / 2;
        } elseif ($alignment == PrintAlign::RIGHT) {
            $x = $this->labelWidth - $textWidth;
        }

        $this->buffer .= "^FO{$x},{$y}\n";
        $this->buffer .= "^A0N,{$fontSize[0]},{$fontSize[1]}\n";
        $this->buffer .= "^FD" . $text . "^FS\n";
    }

    public function addBarcode(int $y, string $data, int $height = 50): void
    {
        $barcodeWidth = strlen($data) * 11;
        $x = ($this->labelWidth - $barcodeWidth) / 2;

        $this->buffer .= "^FO{$x},{$y}\n";
        $this->buffer .= "^BY2\n";
        $this->buffer .= "^BCN,{$height},Y,N,N\n";
        $this->buffer .= "^FD" . $data . "^FS\n";
    }

    public function print(int $copies = 1): void
    {
        $this->buffer .= "^PQ{$copies}\n";
        $this->buffer .= "^XZ\n";
    }
}
