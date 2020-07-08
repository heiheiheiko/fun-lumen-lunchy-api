<?php

namespace Tests;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Assert;

trait TestHelper
{
    protected static $user;
    protected static $authorizedUser;

    // preparations
    public function createUnauthorizedUser()
    {
        $user = factory(User::class)->create([
            'username' => 'stump',
            'email' => 'stump@forest.de',
            'password' => Hash::make('savetherainforest'),
        ]);
        $this->seeInDatabase('users', ['username' => 'stump']);
        return $user;
    }

    protected function createAndAuthorizeUser()
    {
        Self::$user = factory(User::class)->create([
            'username' => 'dagget',
            'email' => 'dagget@beaver.de',
            'password' => Hash::make('baronbadbeaver'),
        ]);
        Self::$authorizedUser = $this->actingAs(Self::$user);
    }

    // assertions
    protected function seeUnauthorized()
    {
        $this->seeStatusCode(401);
        $this->seeJsonContains(['error' => 'Unauthorized']);
    }

    protected function seeJsonCollectionCount($key, $number)
    {
        $decodedResponse = json_decode($this->response->getContent(), true);
        Assert::assertCount($number, $decodedResponse[$key]);
    }
}
