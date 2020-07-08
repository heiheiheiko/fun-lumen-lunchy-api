<?php

namespace Tests;

use App\Models\User;
use Illuminate\Testing\Assert;

trait TestHelper
{
    protected static $user;
    protected static $authorizedUser;

    protected function authorizeUser()
    {
        Self::$user = factory(User::class)->create();
        Self::$authorizedUser = $this->actingAs(Self::$user);
    }

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
