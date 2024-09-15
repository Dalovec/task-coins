<?php

namespace App\Http\Controllers;

use App\Helpers\CoinHelper;
use App\Http\Resources\WatchDogResource;
use App\Models\WatchDog;
use Illuminate\Http\Request;

class WatchDogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return WatchDogResource::collection($request->user()->watchdogs);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (!$request->has('coin_id')) {
            return response()->json(['error' => 'No coin provided'], 400);
        }

        $coin = CoinHelper::getCoin($request->coin_id);

        if (!$coin) {
            return response()->json(['error' => 'Coin not found'], 404);
        }

        $watchDog = WatchDog::query()->firstOrCreate([
            'user_id' => $request->user()->id,
            'coin_id' => $coin->id,
        ]);

        if (!$watchDog->wasRecentlyCreated) {
            return response()->json(['error' => 'Watch dog already exists'], 400);
        }

        return response()->json([
            'message' => 'Watch dog created',
            'coin' => $coin->name,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $coin_id)
    {
        $coin = CoinHelper::getCoin($coin_id);

        if (is_null($coin)) {
            return response()->json(['error' => 'Coin not found'], 404);
        }

        $watchDog = WatchDog::query()
            ->where('coin_id', $coin->id)
            ->where('user_id', $request->user()->id);

        if (!$watchDog->exists()) {
            return response()->json(['error' => 'Watch dog not found'], 404);
        }

        $watchDog->first()->delete();

        return response()->json(['message' => 'Watch dog deleted']);
    }
}
