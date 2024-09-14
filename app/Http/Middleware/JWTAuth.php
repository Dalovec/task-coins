<?php

namespace App\Http\Middleware;

use App\JWTHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JWTAuth
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (!JWTHelper::verifyToken($token)) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        $user = JWTHelper::getTokenUser($token);

        $request->merge(['user' => $user]);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}
