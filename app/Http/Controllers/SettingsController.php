<?php

namespace App\Http\Controllers;

use App\Actions\Settings\UpdateSettings;
use App\Http\Requests\Settings\UpdateSettingsRequest;
use App\Models\Printer;
use App\Models\Settings;
use Native\Laravel\Facades\System;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Settings::with(['labelPrinter', 'receiptPrinter'])->first();
        return inertia('settings', [
            'labelPrinter' => $settings?->labelPrinter,
            'receiptPrinter' => $settings?->receiptPrinter,
            'printers' => Printer::all()
        ]);
    }


    public function update(UpdateSettingsRequest $request, UpdateSettings $updateSettings)
    {
        try {
            $updateSettings->handle($request->validated());
            return to_route('settings.index')->with('success', 'Settings updated successfully');
        } catch (\Throwable $th) {
            dd($th);
            return to_route('settings.index')->with('error', 'Failed to update settings');
        }
    }
}
