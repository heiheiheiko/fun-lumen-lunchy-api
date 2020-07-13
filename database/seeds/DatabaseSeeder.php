<?php

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        Order::truncate();

        $user = factory(User::class)->create([
            'username' => 'oxnard',
            'email' => 'oxnard@montalvo.de',
            'password' => Hash::make('superstar'),
        ]);
        factory(Order::class)->make(['user_id' => $user->id])->save();
    }
}
