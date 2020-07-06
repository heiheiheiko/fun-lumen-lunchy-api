<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;

class UserRepository implements UserRepositoryInterface
{
    public function create($attributes)
    {
        return User::create($attributes);
    }

    public function update($attributes)
    {
        $user = User::findOrFail($attributes->id);
        $user->update($attributes);
        return $user;
    }

    public function all()
    {
        return User::all();
    }

    public function find($id)
    {
        return User::findOrFail($id);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return $user;
    }
}
