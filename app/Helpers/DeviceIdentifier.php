<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DeviceIdentifier
{
    public static function get(): string
    {
        $path = storage_path('app/unique_id.txt');

        if (File::exists($path)) {
            return strtoupper(substr(File::get($path), 0, 8));
        }

        // Generate truly unique ID: timestamp + random
        $unique = time() . Str::random(8);
        File::put($path, $unique);

        return strtoupper(substr($unique, 0, 8));
    }
}
