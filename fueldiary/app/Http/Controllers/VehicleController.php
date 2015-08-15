<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Vehicle;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function __construct()
    {
        $this->middleware('apiauth');
        $this->middleware('ensure.vehicle.owner');
    }

    /**
     * Get a validator
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'user_id' => 'required',
            'registration' => 'required'
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $activeUser = $request->user();
        $usersVehicles = Vehicle::where('user_id', $activeUser->id)
            ->orderBy('registration', 'asc')
            ->get();

        return response()->json($usersVehicles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('vehicle.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $activeUserId = $request->user()->id;
        $registration = strtoupper($request->registration);
        $vehicle = Vehicle::firstOrCreate([
            'user_id' => $activeUserId,
            'registration' => $registration
        ]);

        return response()->json($vehicle);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        try {
            return Vehicle::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        /* not used */
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {
            $vehicle = Vehicle::findOrFail($id);
            $vehicle->registration = strtoupper($request->input('registration', $vehicle->registration));
            $vehicle->save();

            return response()->json($vehicle);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $vehicle = Vehicle::findOrFail($id);
            $vehicle->delete();
            return response('', 204);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
    }
}
