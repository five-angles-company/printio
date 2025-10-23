<?php

namespace App\Actions\Settings;

use App\Models\Settings;

final class UpdateSettings
{
    public function handle(array $data): void
    {
        Settings::updateOrCreate(['id' => 1], $data);
    }
}
