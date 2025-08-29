<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrinterSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'printer_id',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function printer()
    {
        return $this->belongsTo(Printer::class);
    }
}
