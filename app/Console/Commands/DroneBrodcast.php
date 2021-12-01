<?php

namespace App\Console\Commands;

use App\Events\DroneDataUpdated;
use App\Services\DroneService;
use Illuminate\Console\Command;

class DroneBrodcast extends Command
{
    /**
     * @var DroneService
     */
    private DroneService $droneService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DroneService $droneService)
    {
        parent::__construct();
        $this->droneService = $droneService;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drone:broadcast';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Broadcast the available drone data list';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        broadcast(new DroneDataUpdated($this->droneService));
        $this->info('Drone data have been successfully broadcasted.');
        return 0;
    }
}
