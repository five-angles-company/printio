<?php

namespace App\Http\Controllers;

class PrinterController extends Controller
{
    public function index()
    {
        return inertia('printers');
    }
}
