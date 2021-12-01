<?php

namespace App\Services;

use App\Models\Drone;
use App\Notifications\DataDestroyed;
use App\Services\Helper\HasValidator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class DroneService
{
    use HasValidator;

    /**
     * @var MQTTService
     */
    private MQTTService $mqttService;

    /**
     * @param  MQTTService  $mqttService
     */
    public function __construct(MQTTService $mqttService)
    {
        $this->mqttService = $mqttService;
    }

    /**
     * @param  Request  $request
     * @return Drone
     * @throws ValidationException
     */
    public function store(Request $request): Drone
    {
        $this->validate($request, [
            'target_id' => ['required', 'string', 'max:255'],
            'target_type' => ['required', 'string', 'max:255', 'in:drone-a,drone-b,drone-c'],
            'battery' => ['nullable', 'integer'],
            'lat' => ['nullable', 'numeric'],
            'lon' => ['nullable', 'numeric']
        ], attributes: [
            'target_id' => 'ID Drone',
            'target_type' => 'Tipe Drone',
            'battery' => 'Level Baterai',
            'lat' => 'Latitude',
            'lon' => 'Longitude'
        ]);
        if (is_null($request->lat) && is_null($request->lon)) {
            $drone = Drone::updateOrCreate([
                'name' => $request->target_id,
                'type' => $request->target_type ?? 'drone-unknown'
            ], [
                'battery' => (int) $request->battery,
                'updated_at' => Carbon::now()
            ]);
        } else {
            $drone = Drone::updateOrCreate([
                'name' => $request->target_id,
                'type' => $request->target_type ?? 'drone-unknown'
            ], [
                'battery' => (int) $request->battery,
                'lat' => (float) $request->lat,
                'lon' => (float) $request->lon,
                'updated_at' => Carbon::now()
            ]);
        }
        $drone->touch();
        return $drone;
    }

    /**
     * @param  Drone  $drone
     * @return Drone
     * @throws Throwable
     */
    public function destroy(Drone $drone): Drone
    {
        $data = $drone;
        $drone->deleteOrFail();
        auth()->user()?->notify(new DataDestroyed('Drone', Str::title($data->name)));
        return $data;
    }

    /**
     * @param  Request  $request
     * @return Drone|null
     */
    public function resolve(Request $request): ?Drone
    {
        if ($request->query('drone') === '0' || $request->query('drone') === 0) {
            return null;
        }
        return Drone::where('id', $request->query('drone') ? (int) $request->query('drone') : 0)
            ->whereNull('deleted_at')
            ->first();
    }

    /**
     * @param  Request  $request
     * @return Drone|null
     */
    public function single(Request $request): ?Drone
    {
        return $this->collection($request)->first();
    }

    /**
     * @param  Request|null  $request
     * @param  bool  $online
     * @return Collection
     */
    public function collection(?Request $request = null, bool $online = false): Collection
    {
        $query = Drone::orderBy('name')->whereNull('deleted_at');
        if (!is_null($request)) {
            $online = $request->query('online') === '1' ? true : $online;
            $query = $query->when(!empty($request->query('q')), static function ($innerQuery) use ($request) {
                $innerQuery->whereRaw('lower(name) like "%?%"', Str::lower(trim($request->query('q') ?? '')));
            });
        }
        return $query
            ->when($online, static function ($query) {
                $query->where('updated_at', '>=', Carbon::now()->subSeconds(6)->toDateTimeString());
            })
            ->get();
    }

    /**
     * @param  Request  $request
     * @throws ValidationException
     */
    public function status(Request $request): void
    {
        $this->validate($request, [
            'target_id' => ['required', 'string', 'max:255'],
            'target_type' => ['required', 'string', 'max:255', 'in:drone-a,drone-b,drone-c']
        ], attributes: [
            'target_id' => 'ID Drone',
            'target_type' => 'Tipe Drone'
        ]);
        $this->mqttService->publish([
            'action' => 'status',
            'target_id' => $request->target_id,
            'target_type' => $request->target_type
        ]);
    }

    /**
     * @param  Request  $request
     * @throws ValidationException
     */
    public function move(Request $request): void
    {
        $this->validate($request, [
            'target_id' => ['required', 'string', 'max:255'],
            'target_type' => ['required', 'string', 'max:255', 'in:drone-a,drone-b,drone-c'],
            'lat' => ['required', 'numeric'],
            'lon' => ['required', 'numeric']
        ], attributes: [
            'target_id' => 'ID Drone',
            'target_type' => 'Tipe Drone',
            'lat' => 'Latitude',
            'lon' => 'Longitude'
        ]);
        $this->mqttService->publish([
            'action' => 'location',
            'target_id' => $request->target_id,
            'target_type' => $request->target_type,
            'lat' => (float) $request->lat,
            'lon' => (float) $request->lon
        ]);
    }
}
