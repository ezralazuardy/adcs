<?php

Route::group([
    'middleware' => ['authorizable.mqtt']
], static function () {

    /**
     * MQTT api routes
     */
    include 'mqtt/api.php';

});

Route::group([
    'middleware' => ['auth:sanctum', 'verified']
], static function () {

    /**
     * Administrator api routes
     */
    Route::group([
        'middleware' => ['authorizable.administrator']
    ], static function () {

        include 'administrator/api.php';

    });

});

