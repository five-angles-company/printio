<?php

namespace App\Enums;

enum PrintJobStatus: string
{
    case PENDING = 'Pending';
    case COMPLETED = 'Completed';
    case FAILED = 'Failed';
}
