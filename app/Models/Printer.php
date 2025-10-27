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

    protected static function booted()
    {
        static::creating(function ($printer) {
            if (empty($printer->display_name)) {
                $baseName = $printer->name;
                $counter = 1;
                $displayName = $baseName;

                // Keep incrementing until it's unique
                while (self::where('display_name', $displayName)->exists()) {
                    $counter++;
                    $displayName = "{$baseName} {$counter}";
                }

                $printer->display_name = $displayName;
            }
        });
    }

    public function printerSettings()
    {
        return $this->hasOne(PrinterSettings::class);
    }

    public function printJobs()
    {
        return $this->hasMany(PrintJob::class);
    }
}
