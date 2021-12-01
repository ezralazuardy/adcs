<?php

namespace App\Http\Helper;

abstract class MQTTHelper
{
    public static function isValidKey(string $key): bool
    {
        return trim($key) === base64_encode(':'.config('app.key'));
    }
}