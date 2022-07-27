<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsValidEmailAddress implements Rule
{
    public function passes($attribute, $value): bool
    {
        return preg_match_all('/^\S+@\S+\.\S+$/', $value) > 0;
    }

    public function message()
    {
        return 'The given email address is invalid.';
    }
}
