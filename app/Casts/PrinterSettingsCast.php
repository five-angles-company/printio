<?php

namespace App\Casts;

use App\Data\InstructionsSettings;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use App\Data\ReceiptSettings;
use App\Data\LabelSettings;
use App\Enums\PrinterType;

class PrinterSettingsCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        $data = json_decode($value, true) ?? [];

        // Lazy load printer relation if not loaded
        $printer = $model->printer()->first();


        if ($printer && $printer->type === PrinterType::LABEL) {
            return new LabelSettings(...$data);
        }

        if ($printer && $printer->type === PrinterType::INSTRUCTIONS) {
            return new InstructionsSettings(...$data);
        }

        // Default to receipt settings
        return new ReceiptSettings(...$data);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if ($value instanceof ReceiptSettings || $value instanceof LabelSettings || $value instanceof InstructionsSettings) {
            return json_encode($value->toArray());
        }

        return json_encode($value);
    }
}
