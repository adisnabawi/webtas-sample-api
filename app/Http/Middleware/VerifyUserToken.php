<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class VerifyUserToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (empty($request->token)) {
            return response()->json([
                'message' => 'Token not found',
            ], 401);
        }

        try {
            $user = User::where('token', $request->token)
                ->whereNotNull('token')
                ->firstOrFail();
            \Log::info("User logged in: {$user->name}");
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Token / User not found',
            ], 401);
        }
        // append user to request
        $request->merge(['user' => $user]);
        return $next($request);
    }
}
