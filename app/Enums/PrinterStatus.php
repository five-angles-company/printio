<?php

namespace App\Enums;

enum PrinterStatus: string
{
    case READY = 'Ready';
    case BUSY = 'Printer Busy';
    case DOOR_OPEN = 'Printer Door Open';
    case PAPER_OUT = 'Paper Out';
    case OFFLINE = 'Printer Offline';
    case ERROR = 'Error';
    case PRINTING = 'Printing';
}
