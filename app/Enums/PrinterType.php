<?php

namespace App\Enums;

enum PrinterType: string
{
    case LABEL = 'Label';
    case RECEIPT = 'Receipt';
}
