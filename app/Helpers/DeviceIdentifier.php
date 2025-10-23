<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Native\Laravel\Facades\Settings;

class DeviceIdentifier
{
    public static function get(): string
    {
        $uniqueId = Settings::get('app.unique_id');

        if ($uniqueId) {
            return strtoupper(substr($uniqueId, 0, 8));
        }

        // Generate truly unique ID: timestamp + random
        $unique = time() . Str::random(8);
        Settings::set('app.unique_id', $unique);
        return strtoupper(substr($unique, 0, 8));
    }
}
