<?php

namespace App\Http\Controllers;

use App\Helpers\CoinHelper;
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
        return CoinHelper::getCoin($coinId)
            ?? response()->json(['error' => 'Coin not found'], 404);
    }

}
