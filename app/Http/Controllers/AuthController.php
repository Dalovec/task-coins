<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserTokenRequest;
use App\JWTHelper;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AuthController
{
    public function issueToken(Request $request)
    {
        // TODO: Full refactor needed extract logic to helper make it better
        $validator = Validator::make(
            $request->all(),
            UserTokenRequest::rules()
        );

        if ($validator->fails()) {
            return Response::json(['error' => $validator->errors()], 401);
        }

        $user = User::query()->where('email', $request->email);

        if (!$user->exists()) {
            $user = User::query()
                ->create([
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
        }
        else {
            $user = $user->first();
            if (!Hash::check($request->password, $user->password)) {
                return Response::json(['error' => 'Invalid credentials'], 401);
            }
        }

        $token = JWTHelper::issueToken($user);

        return Response::json(['token' => $token]);
    }

    public function revokeToken(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }

        Token::query()
            ->where('token', $token)
            ->delete();

        return Response::json(['message' => 'Token revoked']);
    }

}
