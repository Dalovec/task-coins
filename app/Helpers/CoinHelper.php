<?php

namespace App\Helpers;

use App\Models\Coin;
use Illuminate\Support\Str;

class CoinHelper
{
    public static function getCoin($coin_id): ?Coin
    {
        if (Str::isUuid($coin_id)) {
            return Coin::query()->find($coin_id);
        }

        return Coin::query()->where('coin_id', $coin_id)->first() ?? null;
    }

}
