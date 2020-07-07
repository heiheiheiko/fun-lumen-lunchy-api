<?php

namespace Test\Integration;

use App\Models\Order;
use Tests\ActingUserTestCase;
use Tests\TestHelper;

class OrderControllerTest extends ActingUserTestCase
{
    use TestHelper;

    // index
    public function testIndexShouldReturnAllOrders()
    {
        // preperation
        factory(Order::class)->create(['site' => 'brennholz24.de', 'ordered_at' => '2015-10-21']);
        Self::$actingUser->get('api/v1/orders');

        // assertions
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'site',
                    'updated_at',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
        $this->seeJsonCollectionCount('data', 1);
        $this->seeJsonContains([
            'site' => 'brennholz24.de',
            'ordered_at' => '2015-10-21'
        ]);
    }
}
