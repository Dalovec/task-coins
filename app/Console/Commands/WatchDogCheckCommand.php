<?php

namespace App\Console\Commands;

use App\CoinGeckoApi;
use App\Mail\PriceChanged;
use App\Models\Coin;
use App\Models\WatchDog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class WatchDogCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:watchdog';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all watched currencies and send notifications if price has changed';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $dogs = WatchDog::all();

        $coinList = $dogs->map(fn ($dog) => $dog->coin->coin_id)->unique();

        $this->info('Total number of unique coins watched: ' . $coinList->count());

        $api = new CoinGeckoApi();

        $count = $coinList->count();
        $perPage = 250;

        $reps = ceil($count / $perPage);

        $updatedCoins = collect();

        for ($i = 1; $i <= $reps; $i++) {
            $updatedCoins = $updatedCoins->merge($api->getPagedCoinListById($coinList->toArray(), $i, $perPage));
        }

        $this->info('Total number of coins to update: ' . $updatedCoins->count());
        $this->output->progressStart($updatedCoins->count());
        foreach ($updatedCoins->toArray() as $coin) {
            $coin = Coin::query()
                ->where('coin_id', $coin['id'])
                ->update([
                    'current_price' => $coin['current_price'],
                    'market_cap' => $coin['market_cap'],
                    'price_change_percentage_24h' => $coin['price_change_24h'],
                ]);
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();

        $this->info('Total number of watch dogs to check: ' . $dogs->count());
        $this->output->progressStart($dogs->count());
        foreach ($dogs as $dog) {
            $coin = Coin::query()->find($dog->coin_id);

            $percentChange = $this->getPercentChange($coin->current_price, $dog->set_price);

            if ($percentChange >= $dog->change) {
                Mail::to($dog->user->email)->send(new PriceChanged($coin, $dog));

                $dog->delete();
            }
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();

        $this->info('Everything is up to date!');
    }

    protected function getPercentChange(int $newPrice, int $oldPrice): float|int
    {
        return (abs($newPrice - $oldPrice) / $oldPrice) * 100;
    }
}
