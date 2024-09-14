<?php

namespace App\Console\Commands;

use App\Models\Coin;
use Illuminate\Console\Command;

class CurrencyRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $coinGeckoApi = new \App\CoinGeckoApi();
        $coinList = $coinGeckoApi->getCoinList();

        $this->info('Total number of coins: ' . $coinList->count());

//        $this->output->progressStart($coinList->count());
//
//        foreach ($coinList as $coin) {
//            $coin = Coin::query()->updateOrCreate([
//                'coin_id' => $coin['id'],
//            ], [
//                'name' => $coin['name'],
//                'symbol' => $coin['symbol'],
//            ]);
//
//            $this->output->progressAdvance();
//        }
//
//        $this->output->progressFinish();

        $perPage = 250;
        $coinCount = $coinList->count();

        $reps = ceil($coinCount / $perPage);
        $this->info('Total number of pages: ' . $reps);

        $this->output->progressStart($reps);

        for ($i = 1; $i <= $reps; $i++) {
            $coins = $coinGeckoApi->getPagedCoinList($i, $perPage);
            foreach ($coins as $coin) {
                $coin = Coin::query()->updateOrCreate([
                    'coin_id' => $coin['id'],
                ], [
                    'name' => $coin['name'],
                    'symbol' => $coin['symbol'],
                    'image' => $coin['image'],
                    'current_price' => $coin['current_price'],
                    'price_change_percentage_24h' => $coin['price_change_percentage_24h'],
                    'market_cap' => $coin['market_cap'],
                ]);
            }
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }
}
