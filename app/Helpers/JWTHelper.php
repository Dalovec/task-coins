<?php

namespace App\Helpers;

use App\Models\Token;
use App\Models\User;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHelper
{
    public static function getTokenUser(string $token): ?User
    {
        if(!self::verifyToken($token)){
            return null;
        }

        return Token::query()
            ->where('token', $token)
            ->first()
            ->user;
    }

    public static function deleteToken(string $token): void
    {
        Token::query()
            ->where('token', $token)
            ->delete();
    }

    public static function verifyToken(string $token): bool
    {
        try {
            $token = JWT::decode($token, new Key(config('app.key'), 'HS256'));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function issueOrFind(User $user): \App\JWT
    {
        $token = Token::query()
            ->where('user_id', $user->id);

        if ($token->exists()) {
            try {
                return \App\JWT::fromToken($token->first()->token);

            } catch (ExpiredException $e) {
                $token->first()->delete();

                $jwt = new \App\JWT($user->email);
                Token::query()->create([
                    'user_id' => $user->id,
                    'token' => $jwt->getToken(),
                ]);
            }
        }

        $jwt = new \App\JWT($user->email);
        Token::query()->create([
            'user_id' => $user->id,
            'token' => $jwt->getToken(),
        ]);

        return $jwt;
    }
}
