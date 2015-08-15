<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class VehicleControllerTest extends TestCase
{
    use DatabaseTransactions;

    /*
     * Test that unauthenticated users cannot access and modify vehicles
     */

    public function testUnauthenticatedUserCannotAddVehicle()
    {
        $response = $this->call('POST', '/api/vehicle', ['registration' => 'ABC-123', 'onwer_id' => '1']);
        $this->assertEquals(401, $response->status());
    }

    public function testUnauthenticatedUserCannotListVehicles()
    {
        $response = $this->call('GET', '/api/vehicle');
        $this->assertEquals(401, $response->status());
    }

    public function testUnauthenticatedUserCannotEditVehicle()
    {
        $response = $this->call('PUT', '/api/vehicle/1', ['registration' => 'CBA-321']);
        $this->assertEquals(401, $response->status());
    }

    public function testUnauthenticatedUserCannotDeleteVehicle()
    {
        $response = $this->call('DELETE', '/api/vehicle/1');
        $this->assertEquals(401, $response->status());
    }

    /*
     * Test that authenticated users can create, read, update and delete vehicles
     */
    public function testVehicleCRUD()
    {
        $user = $this->getTestUser();

        // Create vehicle
        $response = $this->actingAs($user)
            ->withSession(['foo' => 'bar'])
            ->call('POST', '/api/vehicle', ['registration' => 'aa-313']);
        $this->assertEquals(200, $response->status());
        $this->seeInDatabase('ta_vehicles', ['registration' => 'AA-313', 'user_id' => $user->id]);

        $createdVehicle = json_decode($response->content());

        // List user's vehicles, make sure the new vehicle is there
        $this->actingAs($user)
            ->withSession(['foo' => 'bar'])
            ->get('/api/vehicle')
            ->seeJson([
                'registration' => 'AA-313',
                'user_id' => $user->id
            ]);

        // Get the created vehicle
        $this->actingAs($user)
            ->withSession(['foo' => 'bar'])
            ->get('/api/vehicle/' . $createdVehicle->id)
            ->seeJson([
                'registration' => 'AA-313',
                'user_id' => $user->id
            ]);

        // Update the vehicle
        $this->actingAs($user)
            ->withSession(['foo' => 'bar'])
            ->put('/api/vehicle/' . $createdVehicle->id, ['registration' => 'abc-555'])
            ->seeJson(['registration' => 'ABC-555']);
        $this->seeInDatabase('ta_vehicles', ['registration' => 'ABC-555', 'user_id' => $user->id]);

        // Delete the vehicle
        $deleteResponse = $this->actingAs($user)
            ->withSession(['foo' => 'bar'])
            ->call('DELETE', '/api/vehicle/' . $createdVehicle->id);
        $this->assertEquals(204, $deleteResponse->status());

        // Make sure the vehicle is not there anymore
        $notFoundResponse = $this->actingAs($user)
            ->withSession(['foo' => 'bar'])
            ->call('GET', '/api/vehicle/' . $createdVehicle->id);
        $this->assertEquals(404, $notFoundResponse->status());
    }

    public function testCannotDeleteNotExistingVehicle()
    {
        $user = $this->getTestUser();

        $response = $this->actingAs($user)
            ->withSession(['foo' => 'bar'])
            ->call('DELETE', '/api/vehicle/99999999');
        $this->assertEquals(404, $response->status());
    }

    public function testCannotUpdateNotExistingVehicle()
    {
        $user = $this->getTestUser();

        $response = $this->actingAs($user)
            ->withSession(['foo' => 'bar'])
            ->call('PUT', '/api/vehicle/99999999', ['registration' => 'ASDFG']);
        $this->assertEquals(404, $response->status());
    }

    public function testOtherUsersVehicleCannotBeUpdated()
    {
        $firstUser = $this->getTestUser();

        $response = $this->actingAs($firstUser)
            ->withSession(['foo' => 'bar'])
            ->call('POST', '/api/vehicle', ['registration' => 'eka-1']);
        $this->assertEquals(200, $response->status());
        $this->seeInDatabase('ta_vehicles', ['registration' => 'EKA-1', 'user_id' => $firstUser->id]);
        $createdVehicle = json_decode($response->content());

        $secondUser = $this->getTestUser();

        // Try to delete the vehicle as a second user
        $deleteResponse = $this->actingAs($secondUser)
            ->withSession(['foo' => 'baz'])
            ->call('PUT', '/api/vehicle/' . $createdVehicle->id, ['registration', 'TOK-2']);
        $this->assertEquals(403, $deleteResponse->status());
        $this->seeInDatabase('ta_vehicles', ['registration' => 'EKA-1', 'user_id' => $firstUser->id]);
    }

    public function testOtherUsersVehicleCannotBeDeleted()
    {
        $firstUser = $this->getTestUser();

        $response = $this->actingAs($firstUser)
            ->withSession(['foo' => 'bar'])
            ->call('POST', '/api/vehicle', ['registration' => 'eka-1']);
        $this->assertEquals(200, $response->status());
        $this->seeInDatabase('ta_vehicles', ['registration' => 'EKA-1', 'user_id' => $firstUser->id]);
        $createdVehicle = json_decode($response->content());

        $secondUser = $this->getTestUser();

        // Try to delete the vehicle as a second user
        $deleteResponse = $this->actingAs($secondUser)
            ->withSession(['foo' => 'baz'])
            ->call('DELETE', '/api/vehicle/' . $createdVehicle->id);
        $this->assertEquals(403, $deleteResponse->status());
        $this->seeInDatabase('ta_vehicles', ['registration' => 'EKA-1', 'user_id' => $firstUser->id]);
    }
}
