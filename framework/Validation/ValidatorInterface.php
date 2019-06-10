<?php
namespace Mkoveni\Lani\Validation;


interface ValidatorInterface
{
    public function validate($data, array $rules);

    public function getErrors();

    public function hasErrors();

    public function registerRules($rules);
}