<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class OtpController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $email = $request->email;
        $password = $request->password;
        
        // Check if user exists
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }
        
        // Verify password
        if (!Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }
        
        // Generate 6-digit OTP
        $otp = rand(100000, 999999);
        
        // Store in Cache for 5 minutes
        Cache::put('otp_' . $email, $otp, 300);
        
        // Send Email
        // In production, use a Queue. For now, send directly.
        try {
            Mail::to($email)->send(new OtpMail($otp));
        } catch (\Exception $e) {
            Log::error("Failed to send OTP email: " . $e->getMessage());
            return response()->json(['message' => 'Failed to send OTP. Please try again.'], 500);
        }

        return response()->json([
            'message' => 'OTP sent successfully to ' . $email
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric'
        ]);

        $email = $request->email;
        $otp = $request->otp;

        $cachedOtp = Cache::get('otp_' . $email);

        if (!$cachedOtp || $cachedOtp != $otp) {
            return response()->json(['message' => 'Invalid or expired OTP'], 400);
        }

        // OTP is valid, clear it
        Cache::forget('otp_' . $email);

        // Find or Create User
        $user = User::firstOrCreate(
            ['email' => $email],
            ['name' => 'User', 'password' => bcrypt(str()->random(16))] // Set a random password for new users
        );

        // Generate Token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }
}
