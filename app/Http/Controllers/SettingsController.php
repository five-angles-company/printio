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
        $labelPrinter = Settings::get('label_printer');
        $receiptPrinter = Settings::get('receipt_printer');

        return inertia('settings', [
            'labelPrinter' => $labelPrinter,
            'receiptPrinter' => $receiptPrinter,
            'printers' => Printer::all()
        ]);
    }


    public function update(UpdateSettingsRequest $request, UpdateSettings $updateSettings)
    {
        try {
            Settings::set('label_printer', $request->input('label_printer'));
            Settings::set('receipt_printer', $request->input('receipt_printer'));

            return to_route('settings.index')->with('success', 'Settings updated successfully');
        } catch (\Throwable $th) {
            dd($th);
            return to_route('settings.index')->with('error', 'Failed to update settings');
        }
    }
}
