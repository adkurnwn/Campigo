<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JWTMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            // First check cookie
            $token = $request->cookie('jwt_token');
            
            // If no cookie, check Authorization header
            if (!$token && $request->header('Authorization')) {
                $token = str_replace('Bearer ', '', $request->header('Authorization'));
            }

            if (!$token) {
                return response()->json(['message' => 'Token not found'], 401);
            }

            // Set token for JWTAuth
            JWTAuth::setToken($token);
            
            $user = JWTAuth::authenticate();
            if (!$user) {
                return response()->json(['message' => 'User not found'], 401);
            }

        } catch (JWTException $e) {
            return response()->json(['message' => 'Token is invalid or expired'], 401);
        }

        return $next($request);
    }
}
