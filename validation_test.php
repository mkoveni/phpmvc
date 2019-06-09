<?php
use Mkoveni\Lani\Validation\Validator;

require 'bootstrap/app.php';

$constraints = [

    'username' => [ 'NotBlank', ['Unique', 'users|email']]
];

$validator = new Validator;

$validator->validate(['username' => 'users'], [
    'username' => ['required', ['unique', ['users', 'email']]]
]);