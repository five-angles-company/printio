<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PrintJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'printer_id',
        'name',
        'type',
        'data',
    ];

    protected $casts = [
        'type' => 'enum:App\Enums\PrinterType',
        'data' => 'array',
    ];

    public function printer()
    {
        return $this->belongsTo(Printer::class);
    }
}
