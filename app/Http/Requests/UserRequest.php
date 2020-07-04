<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

trait UserRequest
{
    protected function validateCreate(Request $request)
    {
        $this->validate($request, [
            'user.name' => 'required|max:50|alpha_num|unique:users,name',
            'user.email' => 'required|email|max:255|unique:users,email',
            'user.password' => 'required|min:8',
        ]);
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            'user.email' => 'required|email|max:255',
            'user.password' => 'required',
        ]);
    }
}
