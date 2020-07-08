<?php

namespace Test\Integration;

use App\Models\User;
use Tests\TestCase;
use Tests\TestHelper;

class UserControllerTest extends TestCase
{
    use TestHelper;

    public static $API_URL = 'api/v1/users';
    public static $RESOURCE_SCHEMA = [
        'data' => [
            'id',
            'username',
            'email',
            'created_at',
            'updated_at',
        ]
    ];
    public static $COLLECTION_SCHEMA = [
        'data' => [
            '*' => [
                'id',
                'username',
                'email',
                'created_at',
                'updated_at',
            ]
        ]
    ];
    public static $EXPECTED_USER = [
        'id' => 1,
        'username' => 'stump',
        'email' => 'stump@forest.de'
    ];

    // create action
    //
    // Given is an unauthorized user
    // When the "create" action is called with valid attributes
    // Then a new "user" should be store and return
    public function test_UnauthorizedUser_CreateAction_CreateUser()
    {
        // preparation
        $this->missingFromDatabase('users', ['username' => 'stump']);
        $body = [
            'user' => [
                'username' => 'stump',
                'email' => 'stump@forest.de',
                'password' => 'savetherainforest'
            ]
        ];
        $this->post(Self::$API_URL, $body);

        // assertions
        $this->seeInDatabase('users', ['username' => 'stump']);
        $this->seeStatusCode(201);
        $this->seeJsonStructure(Self::$RESOURCE_SCHEMA);
        $this->seeJsonContains(Self::$EXPECTED_USER);
    }

    // Given is an unauthorized user
    // When the "create" action is called with invalid attributes
    // Then a new "user" should NOT be store and return a validation message
    public function test_UnauthorizedUser_CreateActionWithInvalidAttributes_NotCreateUser()
    {
        // preparation
        $this->missingFromDatabase('users', ['username' => 'stump']);
        $body = [
            'user' => [
                'email' => 'stump@forest.de',
                'password' => 'savetherainforest'
            ]
        ];
        $this->post(Self::$API_URL, $body);

        // assertions
        $this->seeStatusCode(422);
        $this->seeJsonStructure(['user.username']);
        $this->seeJsonContains(['user.username' => ['The user.username field is required.']]);
    }

    // login action
    //
    // Given is an unauthorized user
    // When the "login" action is called with valid credentials
    // Then a access token should be return
    public function test_UnauthorizedUser_LoginAction_LoginUser()
    {
        // preparation
        $this->createUnauthorizedUser();

        $body = [
            'user' => [
                'email' => 'stump@forest.de',
                'password' => 'savetherainforest'
            ]
        ];
        $this->post(Self::$API_URL . '/login', $body);

        // assertions
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                'token',
                'token_type',
                'expires_in',
            ]
        ]);
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
    // Then all stored "users" should be return
    public function test_AuthorizedUser_IndexAction_ReturnStoredUsers()
    {
        // preparation
        $this->authorizeUser();
        Self::$authorizedUser->get(Self::$API_URL);

        // assertions
        $this->seeStatusCode(200);
        $this->seeJsonStructure(Self::$COLLECTION_SCHEMA);
        $this->seeJsonCollectionCount('data', 1);
        $this->seeJsonContains(Self::$EXPECTED_USER);
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
    // When the "show" action is called with a stored "user.id"
    // Then the "user" should be find and return
    public function test_AuthorizedUser_ShowAction_ReturnStoredUser()
    {
        // preparation
        $this->authorizeUser();
        Self::$authorizedUser->get(Self::$API_URL . '/1');

        // assertions
        $this->seeStatusCode(200);
        $this->seeJsonStructure(Self::$RESOURCE_SCHEMA);
        $this->seeJsonContains(Self::$EXPECTED_USER);
    }
}
