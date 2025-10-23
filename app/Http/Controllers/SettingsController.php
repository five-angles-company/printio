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
            'labelPrinter' => Settings::get('label_printer'),
            'receiptPrinter' => Settings::get('receipt_printer'),
            'instructionsPrinter' => Settings::get('instructions_printer'),
            'apiUrl' => Settings::get('api_url', ''),
            'deviceId' => Settings::get('app.unique_id'),
            'printers' => Printer::all()
        ]);
    }


    public function update(UpdateSettingsRequest $request)
    {
        try {

            isset($request->label_printer) && Settings::set('label_printer', $request->input('label_printer'));
            isset($request->receipt_printer) && Settings::set('receipt_printer', $request->input('receipt_printer'));
            isset($request->instructions_printer) && Settings::set('instructions_printer', $request->input('instructions_printer'));
            if (isset($request->api_url)) {
                Settings::set('api_url', $request->input('api_url'));
                Settings::forget('auth');
            }
            return to_route('settings.index')->with('success', 'Settings updated successfully');
        } catch (\Throwable $th) {
            return to_route('settings.index')->with('error', 'Failed to update settings');
        }
    }
}
