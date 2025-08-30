<?php

namespace App\Enums;


enum PrintDensity: string
{
    case LIGHT = 'light';
    case MEDIUM_LIGHT = 'medium_light';
    case MEDIUM = 'medium';
    case MEDIUM_DARK = 'medium_dark';
    case DARK = 'dark';
}
