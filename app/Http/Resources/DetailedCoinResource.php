<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailedCoinResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'coin_id' => $this->coin_id,
            'name' => $this->name,
            'symbol' => $this->symbol,
            'image' => $this->image,
            'current_price' => (float) $this->current_price,
            'price_change_percentage_24h' => (float) $this->price_change_percentage_24h,
            'market_cap' => (int) $this->market_cap,
        ];
    }
}
