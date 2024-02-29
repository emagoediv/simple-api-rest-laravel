<?php

namespace App\Rules;

class UserValidationRules
{
    public static function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ];
    }
}
