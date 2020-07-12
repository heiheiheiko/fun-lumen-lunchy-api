<?php

namespace App\Http\Validators;

use Illuminate\Support\Facades\Validator;

class UserValidator
{
    protected static $create_rules = [
        'username' => 'required|max:50|alpha_num|unique:users,username',
        'email' => 'required|email|max:255|unique:users,email',
        'password' => 'required|min:8',
    ];

    protected static $authenticate_rules = [
        'email' => 'required|email|max:255',
        'password' => 'required',
    ];

    public static function validateCreate(array $attributes)
    {
        Validator::make($attributes, Self::$create_rules)->validate();
    }

    public static function validateAuthenticate(array $attributes)
    {
        Validator::make($attributes, Self::$authenticate_rules)->validate();
    }
}
