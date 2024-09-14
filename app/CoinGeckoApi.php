<?php

namespace App;

use GuzzleHttp\HandlerStack;
use Illuminate\Support\Collection;
use Spatie\GuzzleRateLimiterMiddleware\RateLimiterMiddleware;

class CoinGeckoApi
{
    /**
     * Create a new class instance.
     */

    private $apiKey;
    private $baseUrl;
    private $client;
    public function __construct()
    {
        $this->apiKey = config('coingecko.api_key');
        $this->baseUrl = config('coingecko.base_url');

        $stack = HandlerStack::create();
        $stack->push(RateLimiterMiddleware::perMinute(10, new RateLimiterStore()));

        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $this->baseUrl,
            'handler' => $stack,
            'headers' => ['x-cg-demo-api-key' => $this->apiKey],
        ]);
    }

    public function ping()
    {
        $response = $this->client->request('GET', 'ping');

        return $response->getStatusCode() === 200;
    }

    public function getCoinList(): Collection
    {
        $response = $this->client->request('GET', 'coins/list');
        return collect(json_decode($response->getBody()->getContents(), true));
    }

   public function getPagedCoinList(int $page = 1, int $perPage = 100): Collection
    {
        $response = $this->client->request('GET', 'coins/markets', ['query' => ['precision' => 4,'page' => $page, 'per_page' => $perPage, 'vs_currency' => 'eur']]);
        return collect(json_decode($response->getBody()->getContents(), true));
    }
}
