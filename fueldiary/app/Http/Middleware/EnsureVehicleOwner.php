<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Vehicle;

class EnsureVehicleOwner
{
    /**
     * Check that the vehicle in the request belongs to the logged in user
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Callable  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $requestUri = $request->getRequestUri();
        $regex = '/^\/.*\/vehicle\/(\d+)\/?.*/';
        $result = preg_match($regex, $requestUri, $matches);

        if ($result === 1)
        {
            $vehicleId = $matches[1];
            $userId = $request->user()->id;

            try {
                $vehicle = Vehicle::findOrFail($vehicleId);
            } catch (ModelNotFoundException $e) {
                return response("Not found", 404);
            }

            if ($vehicle->user_id !== $userId)
            {
                return response("Forbidden", 403);
            }
        }

        return $next($request);
    }
}
