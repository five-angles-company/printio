<?php


namespace App\Encoders\Label;

class ZplEncoder extends BaseEncoder
{
    public function initialize($widthInches = 3, $heightInches = 2)
    {
        parent::initialize($widthInches, $heightInches);
        $this->buffer = "^XA^PW{$this->labelWidth}";
        return $this;
    }

    public function text($content, $x, $y, $size = 20)
    {
        $this->buffer .= "^FO{$x},{$y}^A0N,{$size},{$size}^FD{$content}^FS";
        return $this;
    }

    public function barcode($data, $x, $y)
    {
        $this->buffer .= "^FO{$x},{$y}^BCN,60,Y,N,Y^FD{$data}^FS";
        return $this;
    }

    public function getBuffer(): string
    {
        // ^PQ for copies, then close with ^XZ
        return $this->buffer . "^PQ{$this->getCopies()}^XZ";
    }
}
