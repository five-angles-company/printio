<?php

namespace App\Providers;

use Native\Laravel\Contracts\ProvidesPhpIni;
use Native\Laravel\Facades\App;
use Native\Laravel\Facades\Window;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        App::openAtLogin(true);

        Window::open()
            ->minWidth(1024)
            ->minHeight(700)
            ->maxWidth(1600)
            ->maxHeight(1200);
    }

    /**
     * Return an array of php.ini directives to be set.
     */
    public function phpIni(): array
    {
        return [];
    }
}
