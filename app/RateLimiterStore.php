<?php

namespace App;

use Illuminate\Support\Facades\Cache;
use Spatie\GuzzleRateLimiterMiddleware\Store;

/**
 * Rate limiter store for Spatie Guzzle Rate Limiter Middleware
 * @package App
 */
class RateLimiterStore implements Store
{
    public function get(): array
    {
        return Cache::get('rateLimiter', []);
    }

    public function push(int $timestamp, int $limit): void
    {
        Cache::put('rate-limiter', array_merge($this->get(), [$timestamp]));
    }
}
