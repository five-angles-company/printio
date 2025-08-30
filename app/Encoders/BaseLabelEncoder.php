<?php

namespace App\Encoders;

use App\Enums\PrintAlign;
use App\Enums\PrintDensity;
use App\Enums\PrintSpeed;
use App\Enums\PrintSize;

abstract class BaseLabelEncoder
{
    protected $buffer = '';
    protected $labelWidth = 400;
    protected $labelHeight = 600;
    protected $dpi = 203;

    abstract public function initialize(PrintDensity $density = PrintDensity::MEDIUM, PrintSpeed $speed = PrintSpeed::NORMAL): void;
    abstract public function addText(int $y, string $text, PrintSize $size = PrintSize::MEDIUM, PrintAlign $alignment = PrintAlign::LEFT): void;
    abstract public function addBarcode(int $y, string $data, int $height = 50): void;
    abstract public function print(int $copies = 1): void;

    public function getBuffer(): string
    {
        return $this->buffer;
    }

    public function setLabelSize(int $widthInches, int $heightInches): void
    {
        $this->labelWidth = $widthInches * 203;
        $this->labelHeight = $heightInches * 203;
    }

    public function setDpi(int $dpi): void
    {
        $this->dpi = $dpi;
    }
}
