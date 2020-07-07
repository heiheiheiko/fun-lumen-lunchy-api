<?php

use App\Models\Order;
use App\Models\User;
use Illuminate\Testing\Assert;

class OrderControllerTest extends TestCase
{
    public function testShouldReturnAllOrders()
    {
        $user = factory(User::class)->create();
        factory(Order::class)->create(['site' => 'brennholz24.de', 'ordered_at' => '2015-10-21']);

        $this->actingAs($user)->get('api/v1/orders');
        $this->seeStatusCode(200);
        $this->seeJsonContains([
            'site' => 'brennholz24.de',
            'ordered_at' => '2015-10-21'
        ]);
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
        $decodedResponse = json_decode($this->response->getContent(), true);
        Assert::assertCount(1, $decodedResponse['data']);
    }
}
