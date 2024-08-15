<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\ResetPassword;
use App\Models\User;
use App\Models\PasswordResets;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;


class ForgotPasswordController extends Controller
{

    public function generateToken(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Email not found']);
        }

        $token = Str::random(60);

        $user->forceFill([
            'remember_token' => Hash::make($token),
            'updated_at' => Carbon::now()
        ])->save();


        if (Carbon::now()->diffInMinutes($user->updated_at) > 1440) {
            return response()->json(['message' => 'Token has expired']);
        }

        return $token;
    }

    /**
     * Send a password reset email to the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetEmail(Request $request, $token)
    {
        $this->validate($request, ['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Email not found']);
        }

        Mail::to($request->only('email'))->send(new ResetPassword($token));

        return response()->json(['message' => 'Password reset email sent']);
    }
}