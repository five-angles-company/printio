<?php

namespace App\Http\Controllers;

use App\Http\Requests\Login\LoginRequest;
use App\Helpers\DeviceIdentifier;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Native\Laravel\Facades\Settings;
use Native\Laravel\Facades\System;

class AuthController extends Controller
{
    public function index()
    {
        return inertia('login');
    }

    public function login(LoginRequest $request)
    {
        $baseUrl = config('services.remote_api.url');
        $response = Http::post("{$baseUrl}/api/auth/login", [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            "device_id" => DeviceIdentifier::get(),
        ]);

        if ($response->failed()) {
            dd($response->body());
            Log::error('Login failed: ' . $response->body());
            return to_route('auth.login')->with('error', 'Invalid credentials');
        }
        $data = $response->json();
        Settings::set('auth.token', $data['token']);
        Settings::set('auth.user', $data['user']);

        return to_route('dashboard.index');
    }

    public function logout()
    {
        Settings::set('auth.token', null);
        Settings::set('auth.user', null);

        return to_route('auth.login');
    }
}
