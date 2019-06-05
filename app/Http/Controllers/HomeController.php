<?php

namespace App\Http\Controllers;

use App\Core\Http\RequestInterface;

class HomeController 
{
    public function index()
    {
        echo 'yes';
    }

    public function welcome(RequestInterface $requestInterface)
    {
        return $requestInterface->requestUri;
    }
}