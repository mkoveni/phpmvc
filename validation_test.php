<?php
use Mkoveni\Lani\Validation\Validator;
use Mkoveni\Lani\Helpers\Str;

require 'bootstrap/app.php';

$validator = new Validator;

var_dump(Str::snakeCase('NotBlank'));

// $validator->validate(['username' => 'users'], [
//     'username' => ['required', ['unique', ['users', 'email']]]
// ]);