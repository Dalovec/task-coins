<?php

use App\Console\Commands\WatchDogCheckCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(WatchDogCheckCommand::class)
    ->hourly();
