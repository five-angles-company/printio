<?php

namespace App\Models;

use App\Enums\PrinterStatus;
use App\Enums\PrinterType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'type',
        'status',
    ];

    protected $casts = [
        'status' => PrinterStatus::class,
        'type'   => PrinterType::class,
    ];

    public function printerSettings()
    {
        return $this->hasOne(PrinterSettings::class);
    }

    public function printJobs()
    {
        return $this->hasMany(PrintJob::class);
    }
}
