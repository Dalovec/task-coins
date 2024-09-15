<?php

namespace App;

use Carbon\Carbon;
use Firebase\JWT\Key;

/**
 * JWT class for easy work with JWT tokens
 * @package App
 */
class JWT
{
    private string $user_email;
    private string $iss;
    private string $aud;

    private int $iat;
    private int $exp;
    private int $nbf;
    private string $token;

    public function __construct(
        string $user_email,
        string $iss = null,
        string $aud = null,
        int $iat = null,
        int $exp = null,
        int $nbf = null
    )
    {
        $this->user_email = $user_email;

        $this->iss = $iss ?? config('app.url');
        $this->aud = $aud ?? config('app.url');
        $this->iat = $iat ?? Carbon::now()->timestamp;
        $this->exp = $exp ?? Carbon::now()->addDay()->timestamp;
        $this->nbf = $nbf ?? Carbon::now()->timestamp;

        $this->setToken();
    }

    protected function setToken(): void
    {
        $payload = $this->buildPayload();
        $token = \Firebase\JWT\JWT::encode($payload, config('app.key'), 'HS256');
        $this->token = $token;
    }
    public function getToken(): string
    {
        return $this->token;
    }
    public function getExp(): Carbon
    {
        return Carbon::createFromTimestamp($this->exp);
    }

    protected function buildPayload(): array
    {
        return [
            'user_email' => $this->user_email,
            'iss' => $this->iss,
            'aud' => $this->aud,
            'iat' => $this->iat,
            'exp' => $this->exp,
            'nbf' => $this->nbf,
        ];
    }

    public static function fromToken(string $token): JWT
    {
        $payload = \Firebase\JWT\JWT::decode($token, new Key(config('app.key'), 'HS256'));

        return new JWT(...(array) $payload);
    }
}
