<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MqttKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:key';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the MQTT Device Key for HTTP Header';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $key = config('app.key');
        if (is_null($key)) {
            $this->call('key:generate');
        }
        $this->info('Key: '.base64_encode(':'.$key));
        $this->comment('Use the generated key for HTTP Request Header in your MQTT devices.');
        return 0;
    }
}
