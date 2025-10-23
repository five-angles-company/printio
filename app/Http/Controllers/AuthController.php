<?php

namespace App\Http\Controllers;

use App\Events\UserAuthStateChanged;
use App\Http\Requests\Login\LoginRequest;
use App\Helpers\DeviceIdentifier;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Native\Laravel\Facades\Settings;

class AuthController extends Controller
{
    public function index()
    {
        return inertia('login');
    }

    public function login(LoginRequest $request)
    {
        $baseUrl = Settings::get('api_url');
        try {
            $response = Http::post("{$baseUrl}/api/auth/login", [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                "device_id" => DeviceIdentifier::get(),
            ]);

            if ($response->failed()) {
                Log::error('Login failed: ' . $response->body());
                return to_route('auth.login')->with('error', 'Invalid credentials');
            }
            $data = $response->json();
            Settings::set('auth.token', $data['token']);
            Settings::set('auth.user', $data['user']);
            event(new UserAuthStateChanged());

            return to_route('dashboard.index');
        } catch (\Throwable $th) {
            return to_route('auth.login')->with('error', "Failed to login: please check your settings");
        }
    }

    public function logout()
    {
        Settings::set('auth.token', null);
        Settings::set('auth.user', null);

        return to_route('auth.login');
    }
}
