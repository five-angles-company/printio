<?php

namespace App\Exceptions;

use Exception;

class DuplicatePrintTypeException extends Exception
{
    public function render()
    {
        return to_route('printers.index')->with('error', 'Printer type already exists');
    }
}
