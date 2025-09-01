<?php
// app/Encoders/Label/EPLEncoder.php

namespace App\Encoders\Label;

class EplEncoder extends BaseEncoder
{
    public function initialize($widthInches = 3, $heightInches = 2)
    {
        parent::initialize($widthInches, $heightInches);
        $this->buffer = "q{$this->labelWidth}\nQ{$this->labelHeight}\n";
        return $this;
    }

    public function text($content, $x, $y, $size = 3)
    {
        $this->buffer .= "A{$x},{$y},0,{$size},1,1,N,\"{$content}\"\r\n";
        return $this;
    }

    public function barcode($data, $x, $y)
    {
        $this->buffer .= "B{$x},{$y},0,E30,2,4,60,B,\"{$data}\"\r\n";
        return $this;
    }

    public function getBuffer()
    {
        return "N\r\n" . $this->buffer . "P1\r\n";
    }
}
