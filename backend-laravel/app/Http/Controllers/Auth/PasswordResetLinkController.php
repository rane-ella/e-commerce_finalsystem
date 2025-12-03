<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'phone' => ['required', 'string'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        // Find user by email and phone
        $user = \App\Models\User::where('email', $request->email)
                    ->where('phone', $request->phone)
                    ->first();

        if (!$user) {
            return back()->withInput($request->only('email', 'phone'))
                ->withErrors(['email' => 'We could not find a user with that email and phone number.']);
        }

        // Update password
        $user->forceFill([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'remember_token' => \Illuminate\Support\Str::random(60),
        ])->save();

        return redirect()->route('login')->with('status', 'Your password has been reset!');
    }
}
