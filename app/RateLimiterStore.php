<?php

namespace App;

use Illuminate\Support\Facades\Cache;
use Spatie\GuzzleRateLimiterMiddleware\Store;

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
