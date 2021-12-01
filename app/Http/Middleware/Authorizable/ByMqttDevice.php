<?php

namespace App\Http\Middleware\Authorizable;

use App\Http\Helper\MQTTHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ByMqttDevice
{
    /**
     * Handle an incoming request from MQTT device.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (Str::lower($request->header('User-Agent')) !== 'mqtt') {
            abort('403');
        }
        if (!MQTTHelper::isValidKey($request->header('Key'))) {
            abort('401');
        }
        return $next($request);
    }
}
