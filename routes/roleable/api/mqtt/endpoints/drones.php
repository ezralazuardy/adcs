<?php

use App\Http\Controllers\Administrator\DroneController;

Route::group([
    'as' => 'drones.'
], static function () {

    Route::post('drones', [DroneController::class, 'storeJson'])->name('store');

    Route::delete('drones/{drone}', [DroneController::class, 'destroyJson'])->name('destroy');

});
