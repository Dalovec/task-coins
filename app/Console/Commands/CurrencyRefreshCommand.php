<?php

namespace App\Console\Commands;

use App\CoinGeckoApi;
use App\Models\Coin;
use Illuminate\Console\Command;

class CurrencyRefreshCommand extends Command
{
    const PER_PAGE = 250;

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
    protected $description = 'Creates or updates all coins from CoinGecko to the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $coinGeckoApi = new CoinGeckoApi();
        $coinList = $coinGeckoApi->getCoinList();

        $this->info('Total number of coins: ' . $coinList->count());

        $coinCount = $coinList->count();

        $pageCount = ceil($coinCount / self::PER_PAGE);

        $this->output->progressStart($coinCount);

        for ($i = 1; $i <= $pageCount; $i++) {
            $coins = $coinGeckoApi->getPagedCoinList($i, self::PER_PAGE);

            // This would be much better with an upsert, but it's not really possible with UUIDs as the primary key
            // TODO: Figure out how to do this with upserts and UUIDs

            foreach ($coins as $coinData) {
                $coin = Coin::query()->updateOrCreate([
                    'coin_id' => $coinData['id'],
                ], [
                    'name' => $coinData['name'],
                    'symbol' => $coinData['symbol'],
                    'image' => $coinData['image'],
                    'current_price' => $coinData['current_price'],
                    'price_change_percentage_24h' => $coinData['price_change_percentage_24h'],
                    'market_cap' => $coinData['market_cap'],
                ]);
            }

            $this->output->progressAdvance($coins->count());
        }

        $this->output->progressFinish();
    }
}
