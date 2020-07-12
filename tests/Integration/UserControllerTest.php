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
            'createdAt',
            'updatedAt',
        ]
    ];
    public static $COLLECTION_SCHEMA = [
        'data' => [
            '*' => [
                'id',
                'username',
                'email',
                'createdAt',
                'updatedAt',
            ]
        ]
    ];
    public static $EXPECTED_UNAUTHORIZED_USER = [
        'id' => 1,
        'username' => 'stump',
        'email' => 'stump@forest.de'
    ];
    public static $EXPECTED_AUTHORIZED_USER = [
        'id' => 1,
        'username' => 'dagget',
        'email' => 'dagget@beaver.de'
    ];

    // create action
    //
    // Given is an unauthenticated user
    // When the "create" action is called with valid attributes
    // Then a new "user" should be store and return
    public function test_CallCreate_AsAnUnauthenticatedUser_ShouldCreateAnUser()
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
        $this->seeJsonContains(Self::$EXPECTED_UNAUTHORIZED_USER);
    }

    // Given is an unauthenticated user
    // When the "create" action is called with invalid attributes
    // Then a new "user" should NOT be store and return a validation message
    public function test_CallCreateWithInvalidAttributes_AsAnUnauthenticatedUser_ShouldNotCreateAnUser()
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
        $this->seeJsonStructure(['data' => ['errors']]);
        $this->seeJsonContains(['username' => ['The username field is required.']]);
    }

    // authenticate action
    //
    // Given is an unauthenticated user
    // When the "authenticate" action is called with valid credentials
    // Then a access token should be return
    public function test_CallAuthenticate_AsAnUnauthenticatedUser_ShouldReturnAnAccessToken()
    {
        // preparation
        $this->createUnauthenticatedUser();

        $body = [
            'user' => [
                'email' => 'stump@forest.de',
                'password' => 'savetherainforest'
            ]
        ];
        $this->post(Self::$API_URL . '/authenticate', $body);

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
}
