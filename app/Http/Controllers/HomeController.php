<?php

namespace App\Http\Controllers;

use Mkoveni\Lani\Http\RequestInterface;


class HomeController 
{
    public function index()
    {
        return 'Welcome to my first Simina Framework';
    }

    public function welcome(RequestInterface $requestInterface)
    {
        return $requestInterface->requestUri;
    }
}