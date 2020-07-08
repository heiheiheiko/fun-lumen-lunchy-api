<?php

namespace Test\Integration;

use App\Models\Order;
use Tests\TestCase;
use Tests\TestHelper;

class OrderControllerTest extends TestCase
{
    use TestHelper;

    // create action
    //
    // Given is an authorized user
    // When the "create" action is called with required attributes
    // Then a new order should be store and return
    public function test_AuthorizedUser_CreateAction_CreateOrder()
    {
        // preparation
        $this->authorizeUser();
        $this->missingFromDatabase('orders', ['site' => 'brennholz24.de']);
        $body = [
            'order' => [
                'site' => 'brennholz24.de',
                'ordered_at' => '2015-10-21'
            ]
        ];
        Self::$authorizedUser->post('api/v1/orders', $body);

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

    // Given is an authorized user
    // When the "create" action is called with missing "site"
    // Then a new order should NOT be store and return a validation message
    public function test_AuthorizedUser_CreateActionWithMissingSite_NotCreateOrder()
    {
        // preparation
        $this->authorizeUser();
        $body = ['order' => ['ordered_at' => '2015-10-21']];
        Self::$authorizedUser->post('api/v1/orders', $body);

        // assertions
        $this->seeStatusCode(422);
        $this->seeJsonStructure(['order.site']);
        $this->seeJsonContains(['order.site' => ['The order.site field is required.']]);
    }

    // Given is an authorized user
    // When the "create" action is called with missing "ordered_at"
    // Then new order should NOT be store and return a validation message
    public function test_AuthorizedUser_CreateActionWithMissingOrderedAt_NotCreateOrder()
    {
        // preparation
        $this->authorizeUser();
        $body = ['order' => ['site' => 'brennholz24.de']];
        Self::$authorizedUser->post('api/v1/orders', $body);

        // assertions
        $this->seeStatusCode(422);
        $this->seeJsonStructure(['order.ordered_at']);
        $this->seeJsonContains(['order.ordered_at' => ['The order.ordered at field is required.']]);
    }

    // index action
    //
    // Given is an authorized user
    // When the "index" action is called
    // Then all stored orders should be return
    public function test_AuthorizedUser_IndexAction_ReturnStoredOrders()
    {
        // preparation
        $this->authorizeUser();
        factory(Order::class)->create(['site' => 'brennholz24.de', 'ordered_at' => '2015-10-21']);
        Self::$authorizedUser->get('api/v1/orders');

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

    // show action
    //
    // Given is an authorized user
    // When the "show" action is called with a stored "order.id"
    // Then the order should be find and return
    public function test_AuthorizedUser_ShowAction_ReturnStoredOrder()
    {
        // preparation
        $this->authorizeUser();
        factory(Order::class)->create(['site' => 'brennholz24.de', 'ordered_at' => '2015-10-21']);
        Self::$authorizedUser->get('api/v1/orders/1');

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

    // update actions
    //
    // Given is an authorized user
    // When the "update" action is called with a updated attribute
    // Then the order should be update and return
    public function test_AuthorizedUser_UpdateAction_ReturnUpdatedOrder()
    {
        // preparation
        $this->authorizeUser();
        factory(Order::class)->create(['site' => 'brennholz24.de', 'ordered_at' => '2015-10-21']);
        $this->seeInDatabase('orders', ['site' => 'brennholz24.de']);
        $body = ['order' => ['id' => 1, 'site' => 'palettenShop.de']];
        Self::$authorizedUser->put('api/v1/orders/1', $body);

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

    // delete actions
    //
    // Given is an authorized user
    // When the "delete" action is called with a stored "order.id"
    // Then the order should be delte and return a copy of the deleted order
    public function test_AuthorizedUser_DeleteAction_DeleteStoredOrder()
    {
        // preparation
        $this->authorizeUser();
        factory(Order::class)->create(['site' => 'brennholz24.de', 'ordered_at' => '2015-10-21']);
        $this->seeInDatabase('orders', ['site' => 'brennholz24.de']);
        Self::$authorizedUser->delete('api/v1/orders/1');

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
