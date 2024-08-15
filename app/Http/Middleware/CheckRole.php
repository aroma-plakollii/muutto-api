<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;
use App\Models\Role;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::user();
        $roleNames = explode('|', $role);

        if ($user && $this->userHasAnyRole($user, $roleNames)) {
            return $next($request);
        }

        return response()->json([
            'message' => 'You do not have the required role to access this resource'
        ], 401);
    }

    private function userHasAnyRole($user, $roleNames)
    {
        foreach ($roleNames as $roleName) {
            $role = Role::where('name', $roleName)->first();

            if ($role && $user->hasRole($role->id)) {
                return true;
            }
        }

        return false;
    }
}
