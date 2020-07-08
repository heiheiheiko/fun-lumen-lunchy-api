<?php

namespace Test\Integration;

use App\Models\Order;
use Tests\TestCase;
use Tests\TestHelper;

class OrderControllerTest extends TestCase
{
    use TestHelper;

    public static $API_URL = 'api/v1/orders';
    public static $RESOURCE_SCHEMA = [
        'data' => [
            'id',
            'site',
            'updated_at',
            'created_at',
            'updated_at',
        ]
    ];
    public static $COLLECTION_SCHEMA = [
        'data' => [
            '*' => [
                'id',
                'site',
                'updated_at',
                'created_at',
                'updated_at',
            ]
        ]
    ];
    public static $EXPECTED_ORDER = [
        'id' => 1,
        'site' => 'brennholz24.de',
        'ordered_at' => '2015-10-21'
    ];

    // create action
    //
    // Given is an unauthorized user
    // When the "create" action is called
    // Then just return a validation message
    public function test_UnauthorizedUser_CreateAction_ReturnUnauthorized()
    {
        // preparation
        $this->missingFromDatabase('orders', ['site' => 'brennholz24.de']);
        $body = [
            'order' => [
                'site' => 'brennholz24.de',
                'ordered_at' => '2015-10-21'
            ]
        ];
        $this->post(Self::$API_URL, $body);

        // assertions
        $this->missingFromDatabase('orders', ['site' => 'brennholz24.de']);
        $this->seeUnauthorized();
    }

    // Given is an authorized user
    // When the "create" action is called with valid attributes
    // Then a new "order" should be store and return
    public function test_AuthorizedUser_CreateAction_CreateOrder()
    {
        // preparation
        $this->createAndAuthorizeUser();
        $this->missingFromDatabase('orders', ['site' => 'brennholz24.de']);
        $body = [
            'order' => [
                'site' => 'brennholz24.de',
                'ordered_at' => '2015-10-21'
            ]
        ];
        Self::$authorizedUser->post(Self::$API_URL, $body);

        // assertions
        $this->seeInDatabase('orders', ['site' => 'brennholz24.de']);
        $this->seeStatusCode(201);
        $this->seeJsonStructure(Self::$RESOURCE_SCHEMA);
        $this->seeJsonContains(Self::$EXPECTED_ORDER);
    }

    // Given is an unauthorized user
    // When the "create" action is called with invalid attributes
    // Then a new "order" should NOT be store and return a validation message
    public function test_UnauthorizedUser_CreateActionWithInvalidAttributes_NotCreateOrder()
    {
        // preparation
        $this->createAndAuthorizeUser();
        $body = ['order' => ['ordered_at' => '2015-10-21']];
        Self::$authorizedUser->post(Self::$API_URL, $body);

        // assertions
        $this->seeStatusCode(422);
        $this->seeJsonStructure(['order.site']);
        $this->seeJsonContains(['order.site' => ['The order.site field is required.']]);
    }

    // index action
    //
    // Given is an unauthorized user
    // When the "index" action is called
    // Then return an unauthorized message
    public function test_UnauthorizedUser_IndexAction_ReturnUnauthorized()
    {
        // preparation
        $this->get(Self::$API_URL);

        // assertions
        $this->seeUnauthorized();
    }

    // Given is an authorized user
    // When the "index" action is called
    // Then all stored "orders" should be return
    public function test_AuthorizedUser_IndexAction_ReturnStoredOrders()
    {
        // preparation
        $this->createAndAuthorizeUser();
        factory(Order::class)->create(['site' => 'brennholz24.de', 'ordered_at' => '2015-10-21']);
        Self::$authorizedUser->get(Self::$API_URL);

        // assertions
        $this->seeStatusCode(200);
        $this->seeJsonStructure(Self::$COLLECTION_SCHEMA);
        $this->seeJsonCollectionCount('data', 1);
        $this->seeJsonContains(Self::$EXPECTED_ORDER);
    }

    // show action
    //
    // Given is an unauthorized user
    // When the "show" action is called
    // Then return an unauthorized message
    public function test_UnauthorizedUser_ShowAction_ReturnUnauthorized()
    {
        // preparation
        $this->get(Self::$API_URL . '/1');

        // assertions
        $this->seeUnauthorized();
    }

    // Given is an authorized user
    // When the "show" action is called with a stored "order.id"
    // Then the "order" should be find and return
    public function test_AuthorizedUser_ShowAction_ReturnStoredOrder()
    {
        // preparation
        $this->createAndAuthorizeUser();
        factory(Order::class)->create(['site' => 'brennholz24.de', 'ordered_at' => '2015-10-21']);
        Self::$authorizedUser->get(Self::$API_URL . '/1');

        // assertions
        $this->seeStatusCode(200);
        $this->seeJsonStructure(Self::$RESOURCE_SCHEMA);
        $this->seeJsonContains(Self::$EXPECTED_ORDER);
    }

    // update actions
    //
    // Given is an unauthorized user
    // When the "update" action is called
    // Then return an unauthorized message
    public function test_UnauthorizedUser_UpdateAction_ReturnUnauthorized()
    {
        // preparation
        $this->put(Self::$API_URL . '/1');

        // assertions
        $this->seeUnauthorized();
    }

    // Given is an authorized user
    // When the "update" action is called with a updated attribute
    // Then the "order" should be update and return
    public function test_AuthorizedUser_UpdateAction_ReturnUpdatedOrder()
    {
        // preparation
        $this->createAndAuthorizeUser();
        factory(Order::class)->create(['site' => 'brennholz24.de', 'ordered_at' => '2015-10-21']);
        $this->seeInDatabase('orders', ['site' => 'brennholz24.de']);
        $body = ['order' => ['id' => 1, 'site' => 'palettenShop.de']];
        Self::$authorizedUser->put(Self::$API_URL . '/1', $body);

        // assertions
        $this->seeInDatabase('orders', ['site' => 'palettenShop.de']);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(Self::$RESOURCE_SCHEMA);
        $this->seeJsonContains([
            'site' => 'palettenShop.de',
            'ordered_at' => '2015-10-21'
        ]);
    }

    // delete actions
    //
    // Given is an unauthorized user
    // When the "update" action is called
    // Then return an unauthorized message
    public function test_UnauthorizedUser_DeleteAction_ReturnUnauthorized()
    {
        // preparation
        $this->delete(Self::$API_URL . '/1');

        // assertions
        $this->seeUnauthorized();
    }

    // Given is an authorized user
    // When the "delete" action is called with a stored "order.id"
    // Then the "order" should be delte and return a copy of the deleted "order"
    public function test_AuthorizedUser_DeleteAction_DeleteStoredOrder()
    {
        // preparation
        $this->createAndAuthorizeUser();
        factory(Order::class)->create(['site' => 'brennholz24.de', 'ordered_at' => '2015-10-21']);
        $this->seeInDatabase('orders', ['site' => 'brennholz24.de']);
        Self::$authorizedUser->delete(Self::$API_URL . '/1');

        // assertions
        $this->missingFromDatabase('orders', ['site' => 'brennholz24.de']);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(Self::$RESOURCE_SCHEMA);
        $this->seeJsonContains(Self::$EXPECTED_ORDER);
    }
}
