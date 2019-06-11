<?php

namespace App\Http\Controllers;

use Mkoveni\Lani\Validation\ValidatorInterface;
use Mkoveni\Lani\Http\ResponseInterface;

class HomeController
{
    public function index(ValidatorInterface $validatorInterface, ResponseInterface $response)
    {
        return $response->withJson(['users' => ['Simon', 'Almina', 'Mkoveni', 'Hlengani']]);
        // $validatorInterface->validate(['username' => 's'], [
        //     'username' => ['not_blank']
        // ]);
    }

    public function welcome()
    {
        return 'Welcome to my first Lani App';
    }
}
