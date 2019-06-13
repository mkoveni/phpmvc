<?php

namespace App\Http\Controllers;

use Mkoveni\Lani\Http\ResponseInterface;

class UserController
{
    public function index(ResponseInterface $response)
    {
        return $response->withJson([
            ['name' => 'Simon'], 
            ['name' => 'Rivalani'],
            ['name' => 'Almina']]
        );
    }
}