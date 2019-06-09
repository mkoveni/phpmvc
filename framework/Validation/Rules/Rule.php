<?php

namespace Mkoveni\Lani\Validation\Rules;

abstract class Rule
{

    public abstract function message(): ?string;
    
    public abstract function apply($value): bool;

}