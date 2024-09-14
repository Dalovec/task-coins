<?php

namespace App\Http\Controllers;

use App\Http\Resources\CoinResource;
use App\Models\Coin;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class CoinController extends Controller
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
        if (Str::isUuid($coinId)) {
            return Coin::query()
                ->find($coinId)
                ?? Response::json(['error' => 'Not found'], 404);
        }

        return Coin::query()
            ->where('coin_id', $coinId)
            ->first()
            ?? Response::json(['error' => 'Not found'], 404);
    }

}
