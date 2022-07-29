<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use InvalidArgumentException;

class IsValidEmailAddress implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (! is_string($value)) {
            throw new InvalidArgumentException('The value must be a string!');
        }

        return preg_match_all('/^\S+@\S+\.\S+$/', $value) > 0;
    }

    public function message()
    {
        return 'The given email address is invalid.';
    }
}
