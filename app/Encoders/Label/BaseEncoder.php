<?php

namespace App\Encoders\Label;

abstract class BaseEncoder
{
    protected $buffer = '';
    protected $labelWidth = 600;
    protected $labelHeight = 400;

    public function initialize($widthInches = 3, $heightInches = 2)
    {
        $this->labelWidth = round($widthInches * 203);
        $this->labelHeight = round($heightInches * 203);
        $this->buffer = '';

        return $this;
    }

    abstract public function text($content, $x, $y, $size = null);
    abstract public function barcode($data, $x, $y);
    abstract public function getBuffer();

    public function reset()
    {
        $this->buffer = '';
        $this->labelWidth = 600;
        $this->labelHeight = 400;
        return $this;
    }
}
