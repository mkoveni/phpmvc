<?php

namespace Mkoveni\Lani\Validation\Rules;

class NotBlank extends Rule
{
    public function apply($value): bool
    {
        return !is_null($value) && strlen($value) > 0;
    }

    public function message(): ?string
    {
        return 'Hello mr mkoveni';
    }
} 