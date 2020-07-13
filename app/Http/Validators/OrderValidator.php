<?php

namespace App\Http\Validators;

use Illuminate\Support\Facades\Validator;

class OrderValidator
{
    protected static $CREATE_RULES = [
        'site' => 'required',
        'ordered_at' => 'required',
        'user_id' => 'required',
    ];

    protected static $UPDATE_RULES = [
        'site' => 'sometimes',
        'ordered_at' => 'sometimes',
        'user_id' => 'sometimes',
    ];

    public static function createRules()
    {
        return Self::$CREATE_RULES;
    }

    public static function validateCreate(array $attributes)
    {
        Validator::make($attributes, Self::$CREATE_RULES)->validate();
    }

    public static function validateUpdate(array $attributes)
    {
        Validator::make($attributes, Self::$UPDATE_RULES)->validate();
    }
}
