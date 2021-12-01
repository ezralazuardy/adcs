<?php

use App\Http\Controllers\Administrator\DroneController;

Route::resource('drones', DroneController::class)->only(['index', 'store', 'destroy']);
