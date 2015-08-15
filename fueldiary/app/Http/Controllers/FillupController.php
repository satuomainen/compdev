<?php

namespace App\Http\Controllers;

use App\Fillup;
use App\Http\Requests;
use App\Vehicle;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class FillupController extends Controller
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
            'fillup_date' => 'required',
            'litres' => 'required',
            'amount_paid' => 'required',
            'mileage' => 'required'
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param int $vehicleId
     * @return Response
     */
    public function index(Request $request, $vehicleId)
    {
        $this->assertVehicleExists($vehicleId);
        return Fillup::where(['vehicle_id' => $vehicleId])->get();
    }

    /**
     * Show form for adding a new fillup
     *
     * @param int $vehicleId
     * @return Response
     */
    public function create($vehicleId)
    {
        $vehicle = Vehicle::findOrFail($vehicleId);
        return view('fillup.create')->with('vehicle', $vehicle);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request, $vehicleId)
    {
        $this->assertVehicleExists($vehicleId);
        $fillup = Fillup::firstOrCreate([
            'vehicle_id' => $vehicleId,
            'fillup_date' => $request->fillup_date,
            'litres' => $request->litres,
            'amount_paid' => $request->amount_paid,
            'mileage' => $request->mileage
        ]);
        return response()->json($fillup);
    }

    public function createFillup(Request $request)
    {
        $vehicleId = $request->vehicle_id;
        return $this->store($request, $vehicleId);
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
            return Fillup::findOrFail($id);
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
     * @param  int $vehicleId
     * @param  int $fillupId
     * @return Response
     */
    public function update(Request $request, $vehicleId, $fillupId)
    {
        $this->assertVehicleExists($vehicleId);
        try {
            $fillup = Fillup::findOrFail($fillupId);
            $fillup->fillup_date = $request->input('fillup_date', $fillup->fillup_date);
            $fillup->litres = $request->input('litres', $fillup->litres);
            $fillup->amount_paid = $request->input('amount_paid', $fillup->amount_paid);
            $fillup->mileage = $request->input('mileage', $fillup->mileage);
            $fillup->save();
            return response()->json($fillup);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $vehicleId
     * @param  int $fillupId
     * @return Response
     */
    public function destroy($vehicleId, $fillupId)
    {
        try {
            $fillup = Fillup::findOrFail($fillupId);
            $fillup->delete();
            return response("", 204);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
    }

    private function assertVehicleExists($vehicleId)
    {
        try {
            $vehicle = Vehicle::findOrFail($vehicleId);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
    }
}
