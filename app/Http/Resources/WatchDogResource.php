<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WatchDogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'coin_id' => $this->coin->coin_id,
            'name' => $this->coin->name,
            'symbol' => $this->coin->symbol,
        ];
    }
}
