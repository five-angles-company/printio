<?php

namespace App\Casts;

use App\Data\LabelData;
use App\Data\ReceiptData;
use App\Enums\PrinterType;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class PrintJobDataCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ReceiptData|LabelData|null
    {
        if ($value === null) {
            return null;
        }

        $data = json_decode($value, true);

        return match ($attributes['type']) {
            PrinterType::RECEIPT->value => ReceiptData::from($data),
            PrinterType::LABEL->value => LabelData::from($data),
            default => null,
        };
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): string|null
    {
        if ($value === null) {
            return null;
        }

        return json_encode($value->toArray());
    }
}
