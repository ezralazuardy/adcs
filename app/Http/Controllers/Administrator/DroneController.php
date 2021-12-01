<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Drone;
use App\Services\DroneService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Response;
use Inertia\ResponseFactory;
use Throwable;

class DroneController extends Controller
{
    /**
     * @var DroneService
     */
    private DroneService $droneService;

    /**
     * Create a new Controller instance.
     *
     * @param  DroneService  $droneService
     */
    public function __construct(DroneService $droneService)
    {
        $this->droneService = $droneService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return Response|ResponseFactory
     */
    public function index(Request $request): Response|ResponseFactory
    {
        return inertia('Administrator/Drones/Index', [
            '_drones' => $this->droneService->collection($request, true)->toArray()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->droneService->store($request);
        if (!is_null($request->lat) && !is_null($request->lon)) {
            $this->droneService->move($request);
        }
        return back();
    }

    /**
     * Store a newly created resource in storage and return a JSON response.
     *
     * @param  Request  $request
     * @return string
     * @throws ValidationException
     */
    public function storeJson(Request $request): string
    {
        return $this->droneService->store($request)->toJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Drone  $drone
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(Drone $drone): RedirectResponse
    {
        $this->droneService->destroy($drone);
        return back();
    }

    /**
     * Remove the specified resource from storage and return a JSON response.
     *
     * @param  Drone  $drone
     * @return string
     * @throws Throwable
     */
    public function destroyJson(Drone $drone): string
    {
        return $this->droneService->destroy($drone)->toJson();
    }
}
