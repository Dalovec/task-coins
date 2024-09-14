<?php

namespace App;

use App\Models\Token;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHelper
{

    private $iss;
    private $aud;
    private $iat;

    private $exp;
    private $nbf;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->iss = config('app.url');
        $this->aud = config('app.url');
        $this->iat = Carbon::now()->timestamp;
        $this->exp = Carbon::now()->addDay()->timestamp;
        $this->nbf = Carbon::now()->timestamp;
    }

    public static function issueToken(User $user): string
    {
        $jwtHelper = new JWTHelper();
        $payload = [
            'iss' => $jwtHelper->iss,
            'aud' => $jwtHelper->aud,
            'iat' => $jwtHelper->iat,
            'exp' => $jwtHelper->exp,
            'nbf' => $jwtHelper->nbf,
        ];

        $token = JWT::encode($payload, config('app.key'), 'HS256');

        $tokenModel = Token::query()
            ->firstOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'token' => $token,
                ]);

        return $tokenModel->token;
    }

    public static function getTokenUser(string $token): ?User
    {
        if(!self::verifyToken($token)){
            return null;
        }

        $user = Token::query()
            ->where('token', $token)
            ->first()
            ->user;

        return $user;
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
}
