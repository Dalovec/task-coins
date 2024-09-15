<?php

namespace App\Http\Controllers;

use App\Helpers\CoinHelper;
use App\Http\Resources\CoinResource;
use App\Models\Coin;

class CoinController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CoinResource::collection(Coin::query()->paginate(100));
    }

    /**
     * Display the specified resource.
     */
    public function show(string$coinId)
    {
        return CoinHelper::getCoin($coinId)
            ?? response()->json(['error' => 'Coin not found'], 404);
    }

}
