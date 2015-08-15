<?php

use App\Fillup;
use App\Services\AverageConsumptionService;
use App\Vehicle;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AverageConsumptionServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function testAverageConsumptionCalculation()
    {
        $averageUser = factory('App\User')->create();
        $averageUser->save();

        $vehicle = Vehicle::firstOrCreate([
            'user_id' => $averageUser->id,
            'registration' => 'QWE-234'
        ]);

        $this->assertEquals(0, AverageConsumptionService::getAverageConsumption($vehicle->id));

        $fillups = $this->getFillupData($vehicle->id);

        foreach ($fillups as $fillupData) {
            Fillup::firstOrCreate($fillupData);
        }

        $this->assertEquals(70 / ((2000-1500) / 100), AverageConsumptionService::getAverageConsumption($vehicle->id));
    }

    public function testAverageFillupConsumptionCalculation()
    {
        $averageUser = $this->getTestUser();

        $vehicle = Vehicle::firstOrCreate([
            'user_id' => $averageUser->id,
            'registration' => 'ASD-567'
        ]);

        $fillupsData = $this->getFillupData($vehicle->id);

        $fillups = [];

        foreach ($fillupsData as $fillupData) {
            array_push($fillups, Fillup::firstOrCreate($fillupData));
        }

        $this->assertEquals(0, AverageConsumptionService::getFillupConsumption($fillups[0]->id));
        $this->assertEquals(60 / ((1500-1000) / 100), AverageConsumptionService::getFillupConsumption($fillups[1]->id));
        $this->assertEquals(70 / ((2000-1500) / 100), AverageConsumptionService::getFillupConsumption($fillups[2]->id));
    }

    protected function getFillupData($vehicle_id)
    {
        return [
            ['vehicle_id' => $vehicle_id, 'fillup_date' => '2015-01-01', 'litres' => '50', 'amount_paid' => '65.00', 'mileage' => 1000],
            ['vehicle_id' => $vehicle_id, 'fillup_date' => '2015-02-01', 'litres' => '60', 'amount_paid' => '75.00', 'mileage' => 1500],
            ['vehicle_id' => $vehicle_id, 'fillup_date' => '2015-03-01', 'litres' => '70', 'amount_paid' => '85.00', 'mileage' => 2000],
        ];
    }
}