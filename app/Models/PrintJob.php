<?php

namespace App\Models;

use App\Casts\PrintJobDataCast;
use App\Data\LabelData;
use App\Data\ReceiptData;
use App\Enums\PrinterType;
use App\Enums\PrintJobStatus;
use App\Observers\PrintJobObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


#[ObservedBy([PrintJobObserver::class])]
class PrintJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'printer_id',
        'name',
        'type',
        'status',
        'data',
    ];

    protected $casts = [
        'type' => PrinterType::class,
        'status' => PrintJobStatus::class,
        'data' => PrintJobDataCast::class,
    ];

    public function printer()
    {
        return $this->belongsTo(Printer::class);
    }
}
