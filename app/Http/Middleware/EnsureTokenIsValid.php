<?php

namespace App\Http\Middleware;

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Closure;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('X-Token');
        return UserController::checkTokenStr($token) ? $next($request) : response()->json([
            'success' => false,
            'error' => 'Acesso negado'
        ], 403);
    }
}
