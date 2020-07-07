<?php

namespace Tests;

use Illuminate\Testing\Assert;

trait TestHelper
{
    protected function seeJsonCollectionCount($key, $number)
    {
        $decodedResponse = json_decode($this->response->getContent(), true);
        Assert::assertCount($number, $decodedResponse[$key]);
    }
}
