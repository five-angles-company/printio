<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait WindowsPrinter
{
    protected function dispatchPrintJob(string $file, string $printerName, int $copies = 1, ?string $alias = null): void
    {
        if (!file_exists($file)) {
            Log::error("Print file not found: {$file}");
            return;
        }

        $alias = $alias ?? 'print_' . uniqid();

        // Path to your PowerShell script
        $psScriptPath = base_path('app/Scripts/PrintImage.ps1');

        if (!file_exists($psScriptPath)) {
            Log::error("PowerShell print script not found: {$psScriptPath}");
            return;
        }

        // Properly escape paths for PowerShell
        $escapedFile = escapeshellarg($file);
        $escapedPrinter = escapeshellarg($printerName);

        // Run asynchronously (non-blocking)
        $cmd = sprintf(
            'start /B powershell -ExecutionPolicy Bypass -NoProfile -File "%s" -File %s -Printer %s -Copies %d',
            $psScriptPath,
            $escapedFile,
            $escapedPrinter,
            $copies
        );

        pclose(popen($cmd, 'r'));

        Log::debug("🖨️ Dispatched async PowerShell print", [
            'alias' => $alias,
            'printer' => $printerName,
            'copies' => $copies,
            'file' => $file,
            'script' => $psScriptPath,
        ]);
    }
}
