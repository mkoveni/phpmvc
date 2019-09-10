<?php

namespace App\Http\Controllers;

use Psr\Http\Message\ResponseInterface;


class UserController
{
    public function index($id, ResponseInterface $response)
    {
        return $response->withJson([
            ['name' => 'Simon'], 
            ['name' => 'Rivalani'],
            ['name' => 'Almina']]
        );
    }
}