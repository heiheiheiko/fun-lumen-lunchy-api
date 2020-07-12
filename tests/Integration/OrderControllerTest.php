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
            'updatedAt',
            'createdAt',
            'updatedAt',
        ]
    ];
    public static $COLLECTION_SCHEMA = [
        'data' => [
            '*' => [
                'id',
                'site',
                'updatedAt',
                'createdAt',
                'updatedAt',
            ]
        ]
    ];
    public static $EXPECTED_ORDER = [
        'id' => 1,
        'site' => 'brennholz24.de',
        'orderedAt' => '2015-10-21'
    ];

    // create action
    //
    // Given is an unauthenticated user
    // When the "create" action is called
    // Then just return a validation message
    public function test_CallCreate_AsAnUnauthenticatedUser_ShouldReturnUnauthorized()
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

    // Given is an authenticated user
    // When the "create" action is called with valid attributes
    // Then a new "order" should be store and return
    public function test_CallCreate_AsAnAuthenticatedUser_ShouldCreateAnOrder()
    {
        // preparation
        $this->createAndAuthenticateUser();
        $this->missingFromDatabase('orders', ['site' => 'brennholz24.de']);
        $body = [
            'order' => [
                'site' => 'brennholz24.de',
                'ordered_at' => '2015-10-21'
            ]
        ];
        Self::$authenticatedUser->post(Self::$API_URL, $body);

        // assertions
        $this->seeInDatabase('orders', ['site' => 'brennholz24.de']);
        $this->seeStatusCode(201);
        $this->seeJsonStructure(Self::$RESOURCE_SCHEMA);
        $this->seeJsonContains(Self::$EXPECTED_ORDER);
    }

    // Given is an authenticated user
    // When the "create" action is called with invalid attributes
    // Then a new "order" should NOT be store and return a validation message
    public function test_CallCreateActionWithInvalidAttributes_AsAnUnauthenticatedUser_ShouldNotCreateAnOrder()
    {
        // preparation
        $this->createAndAuthenticateUser();
        $body = ['order' => ['orderedAt' => '2015-10-21']];
        Self::$authenticatedUser->post(Self::$API_URL, $body);

        // assertions
        $this->seeStatusCode(422);
        $this->seeJsonStructure(['errors']);
        $this->seeJsonContains(['site' => ['The site field is required.']]);
    }

    // index action
    //
    // Given is an unauthenticated user
    // When the "index" action is called
    // Then return an unauthenticated message
    public function test_CallIndex_AsAnUnauthenticatedUser_ShouldReturnUnauthorized()
    {
        // preparation
        $this->get(Self::$API_URL);

        // assertions
        $this->seeUnauthorized();
    }

    // Given is an authenticated user
    // When the "index" action is called
    // Then all stored "orders" should be return
    public function test_CallIndex_AsAnAuthenticatedUser_ShouldReturnStoredOrders()
    {
        // preparation
        $this->createAndAuthenticateUser();
        factory(Order::class)->create(['site' => 'brennholz24.de', 'ordered_at' => '2015-10-21']);
        Self::$authenticatedUser->get(Self::$API_URL);

        // assertions
        $this->seeStatusCode(200);
        $this->seeJsonStructure(Self::$COLLECTION_SCHEMA);
        $this->seeJsonCollectionCount('data', 1);
        $this->seeJsonContains(Self::$EXPECTED_ORDER);
    }

    // show action
    //
    // Given is an unauthenticated user
    // When the "show" action is called
    // Then return an unauthenticated message
    public function test_CallShow_AsAnUnauthenticatedUser_ShouldReturnUnauthorized()
    {
        // preparation
        $this->get(Self::$API_URL . '/1');

        // assertions
        $this->seeUnauthorized();
    }

    // Given is an authenticated user
    // When the "show" action is called with a stored "order.id"
    // Then the "order" should be find and return
    public function test_CallShow_AsAnAuthenticatedUser_ShouldReturnAnStoredOrder()
    {
        // preparation
        $this->createAndAuthenticateUser();
        factory(Order::class)->create(['site' => 'brennholz24.de', 'ordered_at' => '2015-10-21']);
        Self::$authenticatedUser->get(Self::$API_URL . '/1');

        // assertions
        $this->seeStatusCode(200);
        $this->seeJsonStructure(Self::$RESOURCE_SCHEMA);
        $this->seeJsonContains(Self::$EXPECTED_ORDER);
    }

    // update actions
    //
    // Given is an unauthenticated user
    // When the "update" action is called
    // Then return an unauthenticated message
    public function test_CallUpdate_AsAnUnauthenticatedUser_ShouldReturnUnauthorized()
    {
        // preparation
        $this->put(Self::$API_URL . '/1');

        // assertions
        $this->seeUnauthorized();
    }

    // Given is an authenticated user
    // When the "update" action is called with a updated attribute
    // Then the "order" should be update and return
    public function test_CallUpdate_AsAnAuthenticatedUser_ShouldReturnAnUpdatedOrder()
    {
        // preparation
        $this->createAndAuthenticateUser();
        factory(Order::class)->create(['site' => 'brennholz24.de', 'ordered_at' => '2015-10-21']);
        $this->seeInDatabase('orders', ['site' => 'brennholz24.de']);
        $body = ['order' => ['id' => 1, 'site' => 'palettenShop.de']];
        Self::$authenticatedUser->put(Self::$API_URL . '/1', $body);

        // assertions
        $this->seeInDatabase('orders', ['site' => 'palettenShop.de']);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(Self::$RESOURCE_SCHEMA);
        $this->seeJsonContains([
            'site' => 'palettenShop.de',
            'orderedAt' => '2015-10-21'
        ]);
    }

    // delete actions
    //
    // Given is an unauthenticated user
    // When the "update" action is called
    // Then return an unauthenticated message
    public function test_CallDelete_AsAnUnauthenticatedUser_ShouldReturnUnauthorized()
    {
        // preparation
        $this->delete(Self::$API_URL . '/1');

        // assertions
        $this->seeUnauthorized();
    }

    // Given is an authenticated user
    // When the "delete" action is called with a stored "order.id"
    // Then the "order" should be delte and return a copy of the deleted "order"
    public function test_CallDelete_AsAnAuthenticatedUser_ShouldDeleteAnOrder()
    {
        // preparation
        $this->createAndAuthenticateUser();
        factory(Order::class)->create(['site' => 'brennholz24.de', 'ordered_at' => '2015-10-21']);
        $this->seeInDatabase('orders', ['site' => 'brennholz24.de']);
        Self::$authenticatedUser->delete(Self::$API_URL . '/1');

        // assertions
        $this->missingFromDatabase('orders', ['site' => 'brennholz24.de']);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(Self::$RESOURCE_SCHEMA);
        $this->seeJsonContains(Self::$EXPECTED_ORDER);
    }
}
