<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Order;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'site' => $faker->url,
        'ordered_at' => date('Y-m-d H:i:s'),
        'user_id' => factory(User::class)->make()->id,
    ];
});
