<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class FillupControllerTest extends TestCase
{
    use DatabaseTransactions;

    /*
     * Test that unauthenticated users cannot access and modify fillups
     */

    public function testUnauthenticatedUserCannotAddFillup()
    {
        $response = $this->call('POST', '/api/vehicle/999999/fillup', [
            'fillup_date' => '2015-01-01',
            'litres' => '31.41',
            'amount_paid' => '58.49',
            'mileage' => '89122'
        ]);
        $this->assertEquals(401, $response->status());
    }

    public function testUnauthenticatedUserCannotListFillups()
    {
        $response = $this->call('GET', '/api/vehicle/999999/fillup');
        $this->assertEquals(401, $response->status());
    }

    public function testUnauthenticatedUserCannotEditFillup()
    {
        $response = $this->call('PUT', '/api/vehicle/999999/fillup/999999', ['mileage' => '89789']);
        $this->assertEquals(401, $response->status());
    }

    public function testUnauthenticatedUserCannotDeleteFillup()
    {
        $response = $this->call('DELETE', '/api/vehicle/999999/fillup/999999');
        $this->assertEquals(401, $response->status());
    }

    /*
     * Test that authenticated user can create, read, update, and delete fillups
     */
    public function testFillupCRUD()
    {
        $user = $this->getTestUser();

        // Create a vehicle
        $response = $this->actingAs($user)
            ->withSession(['foo' => 'bar'])
            ->call('POST', '/api/vehicle', ['registration' => 'FFF-123']);
        $this->assertEquals(200, $response->status());
        $this->seeInDatabase('ta_vehicles', ['registration' => 'FFF-123', 'user_id' => $user->id]);

        $createdVehicle = json_decode($response->content());

        // Create a fillup for the vehicle
        $response = $this->actingAs($user)
            ->withSession(['foo' => 'bar'])
            ->call('POST', '/api/vehicle/' . $createdVehicle->id . "/fillup", [
                'fillup_date' => '2015-01-01',
                'litres' => '12.34',
                'amount_paid' => '56.78',
                'mileage' => '91011'
            ]);
        $this->assertEquals(200, $response->status());
        $this->seeInDatabase('ta_fillups', [
            'fillup_date' => '2015-01-01',
            'litres' => '12.34',
            'amount_paid' => '56.78',
            'mileage' => '91011'
        ]);

        $createdFillup = json_decode($response->content());

        // Get the list of fillups
        $this->actingAs($user)
            ->withSession(['foo' => 'bar'])
            ->visit('/api/vehicle/' . $createdVehicle->id . "/fillup")
            ->seeJson([
                'fillup_date' => '2015-01-01',
                'litres' => '12.34',
                'amount_paid' => '56.78',
                'mileage' => 91011,
                'vehicle_id' => $createdVehicle->id
            ]);

        // Update a fillup
        $this->actingAs($user)
            ->withSession(['foo' => 'bar'])
            ->put('/api/vehicle/' . $createdVehicle->id . "/fillup/" . $createdFillup->id, [
                'amount_paid' => '66.99',
                'mileage' => 91012
            ]);
        $this->seeInDatabase('ta_fillups', [
            'fillup_date' => '2015-01-01',
            'litres' => '12.34',
            'amount_paid' => '66.99',
            'mileage' => 91012,
            'vehicle_id' => $createdVehicle->id
        ]);

        // Delete the fillup
        $deleteResponse = $this->actingAs($user)
            ->withSession(['foo' => 'bar'])
            ->call('DELETE', '/api/vehicle/' . $createdVehicle->id . "/fillup/" . $createdFillup->id);
        $this->assertEquals(204, $deleteResponse->status());
    }

    public function testCannotDeleteNotExistingFillup()
    {
        $user = $this->getTestUser();

        $response = $this->actingAs($user)
            ->withSession(['foo' => 'bar'])
            ->call('DELETE', '/api/vehicle/999999/fillup/999999');
        $this->assertEquals(404, $response->status());
    }

    public function testCannotUpdateNotExistingFillup()
    {
        $user = $this->getTestUser();

        $response = $this->actingAs($user)
            ->withSession(['foo' => 'bar'])
            ->call('PUT', '/api/vehicle/999999/fillup/999999', ['mileage' => '1919191']);
        $this->assertEquals(404, $response->status());
    }

    public function testOtherUsersFillupCannotBeUpdated()
    {
        $firstUser = $this->getTestUser();

        $response = $this->actingAs($firstUser)
            ->withSession(['foo' => 'bar'])
            ->call('POST', '/api/vehicle', ['registration' => 'eka-1']);
        $this->assertEquals(200, $response->status());
        $this->seeInDatabase('ta_vehicles', ['registration' => 'EKA-1', 'user_id' => $firstUser->id]);
        $createdVehicle = json_decode($response->content());

        // Create a fillup for the vehicle
        $response = $this->actingAs($firstUser)
            ->withSession(['foo' => 'bar'])
            ->call('POST', '/api/vehicle/' . $createdVehicle->id . "/fillup", [
                'fillup_date' => '2015-01-01',
                'litres' => '45.67',
                'amount_paid' => '89.01',
                'mileage' => '77000'
            ]);
        $this->assertEquals(200, $response->status());
        $this->seeInDatabase('fillups', [
            'fillup_date' => '2015-01-01',
            'litres' => '45.67',
            'amount_paid' => '89.01',
            'mileage' => 77000
        ]);

        $createdFillup = json_decode($response->content());

        $secondUser = $this->getTestUser();

        // Try to delete the fillup as a second user
        $deleteResponse = $this->actingAs($secondUser)
            ->withSession(['foo' => 'baz'])
            ->call('PUT', '/api/vehicle/' . $createdVehicle->id . "/fillup/" . $createdFillup->id, ['mileage', '77001', 'litres', '99.99']);
        $this->assertEquals(403, $deleteResponse->status());
        $this->seeInDatabase('ta_fillups', [
            'fillup_date' => '2015-01-01',
            'litres' => '45.67',
            'amount_paid' => '89.01',
            'mileage' => 77000
        ]);
    }

    public function testOtherUsersFillupCannotBeDeleted()
    {
        $firstUser = $this->getTestUser();

        $response = $this->actingAs($firstUser)
            ->withSession(['foo' => 'bar'])
            ->call('POST', '/api/vehicle', ['registration' => 'eka-1']);
        $this->assertEquals(200, $response->status());
        $this->seeInDatabase('ta_vehicles', ['registration' => 'EKA-1', 'user_id' => $firstUser->id]);
        $createdVehicle = json_decode($response->content());

        // Create a fillup for the vehicle
        $response = $this->actingAs($firstUser)
            ->withSession(['foo' => 'bar'])
            ->call('POST', '/api/vehicle/' . $createdVehicle->id . "/fillup", [
                'fillup_date' => '2015-01-01',
                'litres' => '22.33',
                'amount_paid' => '44.55',
                'mileage' => '88000'
            ]);
        $this->assertEquals(200, $response->status());
        $this->seeInDatabase('ta_fillups', [
            'fillup_date' => '2015-01-01',
            'litres' => '22.33',
            'amount_paid' => '44.55',
            'mileage' => '88000'
        ]);

        $createdFillup = json_decode($response->content());

        $secondUser = $this->getTestUser();

        // Try to delete the fillup as a second user
        $deleteResponse = $this->actingAs($secondUser)
            ->withSession(['foo' => 'baz'])
            ->call('DELETE', '/api/vehicle/' . $createdVehicle->id . "/fillup/" . $createdFillup->id);
        $this->assertEquals(403, $deleteResponse->status());
        $this->seeInDatabase('ta_fillups', [
            'fillup_date' => '2015-01-01',
            'litres' => '22.33',
            'amount_paid' => '44.55',
            'mileage' => '88000'
        ]);
    }
}