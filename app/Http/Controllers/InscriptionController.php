<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Validation\ValidationException;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class InscriptionController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'confirmed', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $token = $request->validate([
            'g-recaptcha-response' => ['required', 'string'],
        ], [
            'g-recaptcha-response.required' => __('interface.captcha_required'),
        ])['g-recaptcha-response'];

        try {
            $verification = Http::asForm()->timeout(5)->post(
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'secret' => config('services.recaptcha.secret_key'),
                    'response' => $token,
                ]
            );
        } catch (ConnectionException $e) {
            throw ValidationException::withMessages([
                'g-recaptcha-response' => __('interface.captcha_unavailable'),
            ]);
        }

        if (! $verification->successful() || $verification->json('success') !== true) {
            throw ValidationException::withMessages([
                'g-recaptcha-response' => __('interface.captcha_failed'),
            ]);
        }

        $user = User::create($data);
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('accueil');
    }
}
