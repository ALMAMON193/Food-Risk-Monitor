<?php

namespace App\Http\Controllers\API\Auth;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthApiController extends Controller
{
    use ApiResponse;

    //register api
    public function registerApi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'terms_and_conditions' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation failed', $validator->errors()->toArray(), 422);
        }

        try {
            $otp = random_int(1000, 9999);
            $otpExpiresAt = Carbon::now()->addMinutes(10);

            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'otp' => $otp,
                'otp_expires_at' => $otpExpiresAt,
                'is_otp_verified' => false,
                'terms_and_conditions' => $request->input('terms_and_conditions')
            ]);

            $ApiResponse = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_verified' => $user->is_verified ?? 'N/A',
            ];

            $message = "Register Successfully. Please check your email to verify. OTP: " . $otp;
            return $this->sendResponse($ApiResponse, $message);
        } catch (Exception $e) {
            Log::error('Register Error: ' . $e->getMessage());
            return $this->sendError('Registration failed: ' . $e->getMessage(), [], 500);
        }
    }
    public function loginApi(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation failed', $validator->errors()->toArray(), 422);
        }
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return $this->sendError('User Not Found', ['error' => 'User Not Found'], 401);
            }

            if (!Hash::check($request->password, $user->password)) {
                return $this->sendError('Invalid password', ['error' => 'Invalid Password or Email'], 401);
            }

            if (!$user->email_verified_at) {
                return $this->sendError('Email Not Verified', ['error' => 'Email Not Verified'], 401);
            }
            $token = $user->createToken('YourAppName')->plainTextToken;
            $ApiResponse = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'user_type' => $user->user_type,
                'is_verified' => $user->is_verified,

            ];
            $message = "Login Successfully";

            return $this->sendResponse($ApiResponse, $message, $token);
        } catch (Exception $e) {
            Log::error('Login Error: ' . $e->getMessage());
            return $this->sendError('Login failed', ['error' => $e->getMessage()], 500);
        }
    }
    //email verified
    public function verifyEmailApi(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'otp' => 'required|integer|digits:4',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation failed', $validator->errors()->toArray(), 422);
        }
        try {
            // Find the user by email
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return $this->sendError('User not found', ['error' => 'No user found with the provided email'], 404);
            }
            // Check if OTP exists
            if (!$user->otp || !$user->otp_expires_at) {
                return $this->sendError('OTP not set', ['error' => 'No OTP found for this user'], 422);
            }

            // Check if OTP is expired
            if ($user->otp_expires_at < Carbon::now()) {
                return $this->sendError('OTP expired', ['error' => 'The provided OTP has expired'], 422);
            }

            // Check if OTP is valid
            if ($user->otp !== $request->otp) {
                return $this->sendError('Invalid OTP', ['error' => 'The provided OTP is incorrect'], 422);
            }

            // Check if email is already verified
            if ($user->email_verified_at) {
                return $this->sendError('Email already verified', ['error' => 'This email is already verified'], 422);
            }

            // Update user verification status
            $user->is_otp_verified = true;
            $user->email_verified_at = Carbon::now();
            $user->otp = null;
            $user->is_verified = true;
            $user->otp_expires_at = null;
            $user->save();
            // Generate token (Ensure Laravel Sanctum is properly configured: HasApiTokens trait in User model, Sanctum middleware, and personal_access_tokens table)
            $token = $user->createToken('YourAppName')->plainTextToken;

            // Prepare success response
            $ApiResponse = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'user_type' => $user->user_type ?? 'N/A',
                'is_verified' => $user->is_verified ?? 'N/A',
            ];
            $message = "Email verified successfully";

            return $this->sendResponse($ApiResponse, $message, $token);
        } catch (Exception $e) {
            Log::error('Email verification error: ' . $e->getMessage());
            return $this->sendError('An error occurred during verification', ['error' => 'Please try again later'], 500);
        }
    }
    //resend otp
    public function resendOtpApi(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation failed', $validator->errors()->toArray(), 422);
        }
        try {
            // Generate and send OTP to the user's email
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return $this->sendError('User not found', [], 404);
            }
            $otp = rand(1000, 9999);
            $user->otp = $otp;
            $user->otp_expires_at = Carbon::now()->addMinutes(10);
            $user->save();
            // Send OTP email
            //Mail::to($user->email)->send(new OtpMail($otp, $user, 'Verify Your Email Address'));
            $ApiResponse = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'user_type' => $user->user_type ?? 'N/A',
            ];
            $message = "OTP sent successfully : " . $otp;
            return $this->sendResponse($ApiResponse, $message);
        } catch (Exception $e) {
            Log::error('OTP resend error: ' . $e->getMessage());
            return $this->sendError('An error occurred during OTP resend', ['error' => 'Please try again later'], 500);
        }
    }
    //forgotPassword
    public function forgotPasswordApi(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);
        try {
            $email = $request->input('email');
            $otp = random_int(1000, 9999);
            $user = User::where('email', $email)->first();

            if (!$user) {
                return $this->sendError('User Not Found', ['error' => 'User Not Found'], 401);
            }
            // Mail::to($email)->send(new OtpMail($otp, $user, 'Reset Your Password'));
            $user->update([
                'otp' => $otp,
                'otp_expires_at' => Carbon::now()->addMinutes(60),
            ]);
            $ApiResponse = [
                'email' => $email,
                'otp_expires_at' => $user->otp_expires_at,
            ];
            $message = "OTP sent successfully : " . $otp;
            return $this->sendResponse($ApiResponse, $message);
        } catch (Exception $e) {
            Log::error('Failed to send OTP: ' . $e->getMessage());
            return $this->sendError('Failed to send OTP', ['error' => 'Please try again later'], 500);
        }
    }
    //verifyOtpApi
    public function verifyOtpApi(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:4',
        ]);
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return $this->sendError('User Not Found', ['error' => 'User Not Found'], 401);
            }
            if (Carbon::parse($user->otp_expires_at)->isPast()) {
                return $this->sendError('OTP Expired', ['error' => 'OTP Expired'], 401);
            }
            if ($user->otp !== $request->otp) {
                return $this->sendError('Invalid OTP', ['error' => 'Invalid OTP'], 401);
            }
            $token = Str::random(60);
            $user->update([
                'otp' => null,
                'otp_expires_at' => null,
                'reset_password_token' => $token,
                'reset_password_token_expire_at' => Carbon::now()->addHour(),
            ]);
            $ApiResponse = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];
            $message = "OTP sent successfully.Please reset your password ";
            return $this->sendResponse($ApiResponse, $message, $token);
        } catch (Exception $e) {
            Log::error('Failed to verify OTP: ' . $e->getMessage());
            return $this->sendError('Failed to verify OTP', ['error' => 'Please try again later'], 500);
        }
    }
    //resetPasswordApi
    public function resetPasswordApi(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return $this->sendError('User Not Found', ['error' => 'User Not Found'], 401);
            }

            $tokenValid = $user->reset_password_token === $request->token &&
                $user->reset_password_token_expire_at >= Carbon::now();

            if (!$tokenValid) {
                return $this->sendError('Invalid Token', ['error' => 'Invalid Token'], 401);
            }

            $user->update([
                'password' => Hash::make($request->password),
                'reset_password_token' => null,
                'reset_password_token_expire_at' => null,
            ]);
            $message = 'Password reset successfully. Please login with your new password.';
            return $this->sendResponse([], $message);
        } catch (Exception $e) {
            Log::error('Failed to reset password: ' . $e->getMessage());
            return $this->sendError('Failed to reset password', ['error' => 'Please try again later'], 500);
        }
    }
    //logoutApi
    public function logoutApi(Request $request)
    {
        try {
            // Revoke the user's token
            $request->user()->currentAccessToken()->delete();
            // Return a success response
            return $this->sendResponse([], 'Logout successful');
        } catch (Exception $e) {
            Log::error('Logout Error', (array)$e->getMessage());
            return $this->sendError('An error occurred during logout', ['error' => 'Please try again later'], 500);
        }
    }
}
