<?php

use App\Events\DroneDataUpdated;
use App\Services\DroneService;

Route::group([
    'as' => 'drones.'
], static function () {

    Route::get('broadcast', static function (DroneService $droneService) {
        broadcast(new DroneDataUpdated($droneService));
    })->name('broadcast');

});
