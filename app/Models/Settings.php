<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $fillable = [
        'label_printer',
        'receipt_printer',
        'client_id',
        'token',
    ];

    public function labelPrinter()
    {
        return $this->belongsTo(Printer::class, 'label_printer');
    }

    public function receiptPrinter()
    {
        return $this->belongsTo(Printer::class, 'receipt_printer');
    }
}
