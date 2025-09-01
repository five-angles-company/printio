<?php

namespace App\Encoders\Label;

class TsplEncoder extends BaseEncoder
{
    protected $labelWidthInches = 3;
    protected $labelHeightInches = 2;

    public function initialize($widthInches = 3, $heightInches = 2)
    {
        $this->labelWidthInches = $widthInches;
        $this->labelHeightInches = $heightInches;
        $this->labelWidth = round($widthInches * 203);
        $this->labelHeight = round($heightInches * 203);
        $this->buffer = "SIZE {$widthInches},{$heightInches}\nCLS\n";
        return $this;
    }

    public function text($content, $x, $y, $size = 1)
    {
        $fontSize = round($size / 2); // Adjust size for TSPL
        $this->buffer .= "TEXT {$x},{$y},\"3\",0,{$fontSize},{$fontSize},\"{$content}\"\r\n";
        return $this;
    }

    public function barcode($data, $x, $y)
    {
        $this->buffer .= "BARCODE {$x},{$y},\"128\",60,1,0,2,2,\"{$data}\"\r\n";
        return $this;
    }

    public function getBuffer()
    {
        return $this->buffer . "PRINT 1\r\n";
    }
}
