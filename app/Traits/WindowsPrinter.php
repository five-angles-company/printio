<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait WindowsPrinter
{
    protected function dispatchPrintJob(
        string $file,
        string $printerName,
        int $copies = 1,
        bool $blocking = false,
        int $maxHeightMm = 800
    ): void {
        if (!file_exists($file)) {
            Log::error("Print file not found: {$file}");
            return;
        }

        $alias = 'print_' . uniqid();

        $psScriptPath = base_path('app/Scripts/PrintImage.ps1');

        if (!file_exists($psScriptPath)) {
            Log::error("PowerShell print script not found: {$psScriptPath}");
            return;
        }

        // Safer Windows quoting
        $escapedFile = '"' . addslashes($file) . '"';
        $escapedPrinter = '"' . addslashes($printerName) . '"';

        // Proper argument passing with maxHeightMm for pagination
        $cmd = sprintf(
            'powershell -ExecutionPolicy Bypass -NoProfile -File "%s" -filePath %s -printerName %s -copies %d -maxHeightMm %d',
            $psScriptPath,
            $escapedFile,
            $escapedPrinter,
            $copies,
            $maxHeightMm
        );

        if (!$blocking) {
            // Run async (non-blocking)
            $cmd = 'start /B ' . $cmd;
            pclose(popen($cmd, 'r'));
        } else {
            // Run blocking
            exec($cmd, $output, $exitCode);
            Log::info("Print job finished", compact('alias', 'printerName', 'exitCode', 'output'));
        }

        Log::info("Dispatched PowerShell print", [
            'alias' => $alias,
            'printer' => $printerName,
            'copies' => $copies,
            'maxHeightMm' => $maxHeightMm,
            'file' => $file,
            'script' => $psScriptPath,
            'cmd' => $cmd,
        ]);
    }
}
