<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return RedirectResponse
     */
    public function index(): RedirectResponse
    {
        if (auth()->user()?->load('role')->role->isAdministrator()) {
            return redirect()->route('drones.index');
        }
        return redirect()->route('login');
    }
}
