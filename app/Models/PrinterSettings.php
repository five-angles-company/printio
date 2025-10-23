<?php

namespace App\Models;

use App\Casts\PrinterSettingsCast;
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
        'settings' => PrinterSettingsCast::class,
    ];

    public function printer()
    {
        return $this->belongsTo(Printer::class);
    }
}
