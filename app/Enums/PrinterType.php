<?php

namespace App\Enums;

enum PrinterType: string
{
    case LABEL = 'Label';
    case RECEIPT = 'Receipt';
    case INSTRUCTIONS = 'Instructions';
    case POS_SESSION = 'POS Session';
}
