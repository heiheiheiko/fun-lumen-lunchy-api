<?php

namespace Test\Integration;

use App\Models\Order;
use Tests\ActingUserTestCase;
use Tests\TestHelper;

class OrderControllerTest extends ActingUserTestCase
{
    use TestHelper;

    // create
    public function test_CreateAction_ShouldCreateAndReturnAnOrder()
    {
        // preperation
        $this->missingFromDatabase('orders', ['site' => 'brennholz24.de']);
        $body = [
            'order' => [
                'site' => 'brennholz24.de',
                'ordered_at' => '2015-10-21'
            ]
        ];
        Self::$actingUser->post('api/v1/orders', $body);

        // assertions
        $this->seeInDatabase('orders', ['site' => 'brennholz24.de']);
        $this->seeStatusCode(201);
        $this->seeJsonStructure([
            'data' => [
                'id',
                'site',
                'updated_at',
                'created_at',
                'updated_at',
            ]
        ]);
        $this->seeJsonContains([
            'site' => 'brennholz24.de',
            'ordered_at' => '2015-10-21'
        ]);
    }

    public function test_CreateAction_ShouldNotCreateAnOrderWhenSiteIsMissing()
    {
        // preperation
        $body = ['order' => ['ordered_at' => '2015-10-21']];
        Self::$actingUser->post('api/v1/orders', $body);

        // assertions
        $this->seeStatusCode(422);
        $this->seeJsonStructure(['order.site']);
        $this->seeJsonContains(['order.site' => ['The order.site field is required.']]);
    }

    public function test_CreateAction_ShouldNotCreateAnOrderWhenOrderedAtIsMissing()
    {
        // preperation
        $body = ['order' => ['site' => 'brennholz24.de']];
        Self::$actingUser->post('api/v1/orders', $body);

        // assertions
        $this->seeStatusCode(422);
        $this->seeJsonStructure(['order.ordered_at']);
        $this->seeJsonContains(['order.ordered_at' => ['The order.ordered at field is required.']]);
    }

    // index
    public function test_IndexAction_ShouldReturnAllOrders()
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

    // show
    public function test_ShowAction_ShouldReturnAnOrder()
    {
        // preperation
        factory(Order::class)->create(['site' => 'brennholz24.de', 'ordered_at' => '2015-10-21']);
        Self::$actingUser->get('api/v1/orders/1');

        // assertions
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                'id',
                'site',
                'updated_at',
                'created_at',
                'updated_at',
            ]
        ]);
        $this->seeJsonContains([
            'site' => 'brennholz24.de',
            'ordered_at' => '2015-10-21'
        ]);
    }

    // update
    public function test_UpdateAction_ShouldUpdateAnExistingOrderAndReturnIt()
    {
        // preperation
        factory(Order::class)->create(['site' => 'brennholz24.de', 'ordered_at' => '2015-10-21']);
        $this->seeInDatabase('orders', ['site' => 'brennholz24.de']);
        $body = ['order' => ['id' => 1, 'site' => 'palettenShop.de']];
        Self::$actingUser->put('api/v1/orders/1', $body);

        // assertions
        $this->seeInDatabase('orders', ['site' => 'palettenShop.de']);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                'id',
                'site',
                'updated_at',
                'created_at',
                'updated_at',
            ]
        ]);
        $this->seeJsonContains([
            'site' => 'palettenShop.de',
            'ordered_at' => '2015-10-21'
        ]);
    }

    // update
    public function test_DeleteAction_ShouldDeleteAnExistingOrderAndReturnIt()
    {
        // preperation
        factory(Order::class)->create(['site' => 'brennholz24.de', 'ordered_at' => '2015-10-21']);
        $this->seeInDatabase('orders', ['site' => 'brennholz24.de']);
        Self::$actingUser->delete('api/v1/orders/1');

        // assertions
        $this->missingFromDatabase('orders', ['site' => 'brennholz24.de']);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                'id',
                'site',
                'updated_at',
                'created_at',
                'updated_at',
            ]
        ]);
        $this->seeJsonContains([
            'site' => 'brennholz24.de',
            'ordered_at' => '2015-10-21'
        ]);
    }
}
