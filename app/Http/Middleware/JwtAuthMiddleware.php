<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user_token = JWTAuth::parseToken();
            $authenticated = $user_token->authenticate();

            if (!$authenticated) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } catch (JWTException $e) {
            if ($e->getMessage() == 'The token has been blacklisted') {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            return response()->json(['message' => $e->getMessage()], 401);
        }

        return $next($request);
    }
}
