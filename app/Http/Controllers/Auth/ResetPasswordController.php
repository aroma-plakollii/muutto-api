<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Carbon\Carbon;


class ResetPasswordController extends Controller
{
    /**
     * Show the password reset form.
     *
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function showResetForm($token)
    {
        return view('auth.passwords.reset', [
            'token' => $token,
        ]);
    }

    /**
     * Reset the password for the given token and email address.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request, $token)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required|same:password',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return;
        }

        if (Carbon::now()->diffInMinutes($user->updated_at) > 1440) {
            return response()->json(['message' => 'Token has expired']);
        }
    
        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => null,
        ])->save();
    
        return response()->json(['message' => 'Password reset successful']);
    }
}