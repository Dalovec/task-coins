<?php

namespace App\Http\Middleware;

use App\Helpers\JWTHelper;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class JWTAuth
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $token = $request->bearerToken();

        if (!$token) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }

        // TODO: Add Response to expired and invalid tokens
        if (!JWTHelper::verifyToken($token)) {
            return Response::json(['error' => 'Invalid token'], 401);
        }

        $user = JWTHelper::getTokenUser($token);

        $request->merge(['user' => $user]);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}
