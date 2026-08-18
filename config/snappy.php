<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Snappy PDF / Image Configuration
    |--------------------------------------------------------------------------
    |
    | This option contains settings for PDF generation.
    |
    | Enabled:
    |    
    |    Whether to load PDF / Image generation.
    |
    | Binary:
    |    
    |    The file path of the wkhtmltopdf / wkhtmltoimage executable.
    |
    | Timeout:
    |    
    |    The amount of time to wait (in seconds) before PDF / Image generation is stopped.
    |    Setting this to false disables the timeout (unlimited processing time).
    |
    | Options:
    |
    |    The wkhtmltopdf command options. These are passed directly to wkhtmltopdf.
    |    See https://wkhtmltopdf.org/usage/wkhtmltopdf.txt for all options.
    |
    | Env:
    |
    |    The environment variables to set while running the wkhtmltopdf process.
    |
    */

    'pdf' => [
        'enabled' => false,
        'binary'  => env('WKHTML_PDF_BINARY', '/usr/local/bin/wkhtmltopdf'),
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],

    'image' => [
        'enabled' => true,
        // Quoted: Snappy passes the binary through the shell unquoted, so an
        // install path containing spaces (e.g. C:\Users\Almoharib Pharmacy\...)
        // breaks with "is not recognized as an internal or external command".
        'binary'  => '"' . trim(env('WKHTML_IMG_BINARY', base_path('bin/wkhtmltoimage.exe')), '"') . '"',
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],

];
