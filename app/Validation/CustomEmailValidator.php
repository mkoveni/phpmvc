<?php

namespace App\Validation;

use Mkoveni\Lani\Validation\Rules\Rule;

class CustomEmailValidator extends Rule
{
    public function apply($value): bool
    {
        return false;
    }

    public function message(): ?string
    {
        return 'Email is not valid.';
    }
}