<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\User;
use Illuminate\Http\Request;

class UserRepository implements UserRepositoryInterface
{
    public function create(Request $request)
    {
        return User::create($request->all());
    }

    public function update(Request $request, $id)
    {
        return User::findOrFail($id)->update($request->all());
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
