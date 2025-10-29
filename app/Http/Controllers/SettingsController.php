<?php

namespace App\Http\Controllers;

use App\Actions\Settings\UpdateSettings;
use App\Http\Requests\Settings\UpdateSettingsRequest;
use App\Models\Printer;
use Native\Laravel\Facades\Settings;

class SettingsController extends Controller
{
    public function index()
    {
        return inertia('settings', [
            'apiUrl' => Settings::get('api_url', ''),
            'deviceId' => Settings::get('app.unique_id'),
        ]);
    }


    public function update(UpdateSettingsRequest $request)
    {

        if (isset($request->api_url)) {
            Settings::set('api_url', $request->input('api_url'));
            Settings::forget('auth');
        }
        return to_route('settings.index')->with('success', 'Settings updated successfully');
    }
}
