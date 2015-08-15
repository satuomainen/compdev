<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Vehicle;

class MainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['getWelcomePage']]);
    }

    public function getWelcomePage(Request $request)
    {
        if (Auth::check()) {
            return redirect('/home');
        }
        return view('welcome');
    }

    public function getHomeView(Request $request)
    {
        $activeUser = $request->user();
        $usersVehicles = Vehicle::where('user_id', $activeUser->id)
            ->orderBy('registration', 'asc')
            ->get();
        return view('home')->with(['activeUser' => $activeUser, 'vehicles' => $usersVehicles]);
    }

    public function getAddFillupView(Request $request, $vehicleId)
    {
        $activeUser = $request->user();

        $vehicle = Vehicle::findOrFail($vehicleId);
        return view('fillup.create')->with(['activeUser' => $activeUser, 'vehicle' => $vehicle]);
    }
}