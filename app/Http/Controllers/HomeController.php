<?php

namespace App\Http\Controllers;

use Config;
use Psr\Http\Message\ResponseInterface;
use Mkoveni\Lani\Validation\ValidatorInterface;

class HomeController
{
    public function index(ValidatorInterface $validatorInterface, ResponseInterface $response)
    {
       
        // $validatorInterface->validate(['username' => 's'], [
        //     'username' => ['not_blank']
        // ]);

        return $response->withJson(Config::get('app'));
    }

    public function welcome(ResponseInterface $response)
    {
        return $response->withJson(['message' => 'Welcome to my first Lani App']);
    }
}
