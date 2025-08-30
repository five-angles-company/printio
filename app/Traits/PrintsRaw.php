<?php

namespace App\Traits;

trait PrintsRaw
{
    protected string $printerExe;

    protected function initializePrinter(): void
    {
        $this->printerExe = base_path('bin/win-printer.exe');
    }

    /**
     * Send raw data to a printer.
     *
     * @param string $printerName
     * @param string $data
     * @param string $jobName
     * @param bool $async If true, returns immediately
     * @return array ['success' => bool, 'output' => string, 'error' => string]
     */
    protected function printRaw(string $printerName, string $data, string $jobName = 'Print Job', bool $async = false): array
    {
        $this->initializePrinter();

        $descriptors = [
            0 => ["pipe", "r"], // STDIN
            1 => ["pipe", "w"], // STDOUT
            2 => ["pipe", "w"], // STDERR
        ];

        $process = proc_open(
            [$this->printerExe, 'print', $printerName, $jobName],
            $descriptors,
            $pipes
        );

        if (!is_resource($process)) {
            return [
                'success' => false,
                'output' => '',
                'error' => 'Failed to start printer process',
            ];
        }

        // Send raw data
        fwrite($pipes[0], $data);
        fclose($pipes[0]); // EOF

        $output = '';
        $error = '';

        if (!$async) {
            $output = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            $error = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            proc_close($process);
        } else {
            // Async: close pipes and return immediately
            fclose($pipes[1]);
            fclose($pipes[2]);
        }

        return [
            'success' => $error === '',
            'output' => $output,
            'error' => $error,
        ];
    }
}
