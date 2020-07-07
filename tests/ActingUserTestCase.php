<?php

namespace Tests;

use App\Models\User;

abstract class ActingUserTestCase extends TestCase
{
    protected static $user;
    protected static $actingUser;

    protected function setUp(): void
    {
        parent::setUp();
        Self::$user = factory(User::class)->create();
        Self::$actingUser = $this->actingAs(Self::$user);
    }
}
