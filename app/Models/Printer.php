<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'status',
    ];

    protected $casts = [
        'status' => 'enum:App\Enums\PrinterStatus',
        'type' => 'enum:App\Enums\PrinterType',
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
