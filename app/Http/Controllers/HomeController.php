<?php

namespace App\Http\Controllers;

use Mkoveni\Lani\Validation\ValidatorInterface;


class HomeController
{
    public function index(ValidatorInterface $validatorInterface)
    {
        $validatorInterface->validate(['username' => 's'], [
            'username' => ['not_blank']
        ]);
    }

    public function welcome()
    {
        return 'Welcome to my first Lani App';
    }
}
