<?php

namespace App\Services;

use App\Services\Helper\HasValidator;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;
use Throwable;

class MQTTService
{
    use HasValidator;

    /**
     * @var MqttClient
     */
    public MqttClient $mqtt;

    /**
     * @var ConnectionSettings
     */
    public ConnectionSettings $settings;

    /**
     * @var int
     */
    public int $qos;

    /**
     * @var string
     */
    public string $backendChannel;

    public function __construct()
    {
        $this->qos = 0;
        $this->backendChannel = config('app.hivemq_cloud_broker_backend_channel');
        try {
            $this->mqtt = new MqttClient(
                config('app.hivemq_cloud_broker_host'),
                config('app.hivemq_cloud_broker_port'),
                config('app.hivemq_cloud_broker_cliend_id')
            );
            $this->settings = (new ConnectionSettings)
                ->setUseTls(true)
                ->setTlsSelfSignedAllowed(true)
                ->setUsername(config('app.hivemq_cloud_broker_username'))
                ->setPassword(config('app.hivemq_cloud_broker_password'));
        } catch (Throwable $e) {
            report($e);
        }
    }

    /**
     * @param  array  $data
     * @param  string|null  $channel
     */
    public function publish(array $data, ?string $channel = null): void
    {
        try {
            $this->mqtt->connect($this->settings);
            $this->mqtt->publish(
                $channel ?? $this->backendChannel,
                json_encode($data, JSON_THROW_ON_ERROR),
                $this->qos
            );
            $this->mqtt->disconnect();
        } catch (Throwable $e) {
            report($e);
        }
    }
}
