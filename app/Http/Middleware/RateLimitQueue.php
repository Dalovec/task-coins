<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class RateLimitQueue
{
protected $key;
protected $limit;
protected $decay;

public function __construct($key, $limit, $decay)
{
$this->key = $key;
$this->limit = $limit;  // Max requests allowed
$this->decay = $decay;  // Decay period in seconds
}

public function handle($job, $next)
{
// Unique key for rate-limiting (could be user ID, API key, etc.)
$key = Str::lower($this->key);

// Check if the job has exceeded rate limits
if (RateLimiter::tooManyAttempts($key, $this->limit)) {
// Release the job for retry after the decay period
$job->release($this->decay - RateLimiter::availableIn($key));
return;
}

// Increment the attempt count
RateLimiter::hit($key, $this->decay);

// Process the job
$next($job);
}
}
