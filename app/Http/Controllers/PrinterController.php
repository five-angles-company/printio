<?php

namespace App\Http\Controllers;

use App\Actions\Printer\CreatePrinter;
use App\Actions\Printer\DeletePrinter;
use App\Actions\Printer\UpdatePrinter;
use App\Http\Requests\Printer\CreatePrinterRequest;
use App\Http\Requests\Printer\UpdatePrinterRequest;
use App\Models\Printer;
use Native\Laravel\Facades\System;

class PrinterController extends Controller
{
    public function index()
    {
        $systemPrinters = System::printers();
        $printers = Printer::all();

        return inertia('printers', [
            'printers' => $printers,
            'systemPrinters' => $systemPrinters
        ]);
    }

    public function store(CreatePrinterRequest $request, CreatePrinter $createPrinter)
    {
        try {
            $printer = $createPrinter->handle($request->validated());
            return to_route('printers.index')->with('success', "$printer->name created successfully");
        } catch (\Throwable $th) {
            dd($th);
            return to_route('printers.index')->with('error', $th->getMessage());
        }
    }

    public function update(UpdatePrinterRequest $request, Printer $printer, UpdatePrinter $updatePrinter)
    {
        try {
            $updatePrinter->handle($printer, $request->validated());
            return to_route('printers.index')->with('success', "$printer->name updated successfully");
        } catch (\Throwable $th) {
            return to_route('printers.index')->with('error', $th->getMessage());
        }
    }

    public function destroy(Printer $printer, DeletePrinter $deletePrinter)
    {
        try {
            $deletePrinter->handle($printer);
            return to_route('printers.index')->with('success', "$printer->name deleted successfully");
        } catch (\Throwable $th) {
            return to_route('printers.index')->with('error', $th->getMessage());
        }
    }
}
