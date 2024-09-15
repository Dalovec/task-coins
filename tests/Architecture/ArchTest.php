<?php

arch()
    ->expect('App\Http\Controllers')
    ->toBeClasses()
    ->not()->toExtend(\App\Http\Controllers\Controller::class);

arch()->preset()->laravel();

arch()->preset()->security();
arch()->preset()->relaxed();
