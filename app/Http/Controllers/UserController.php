<?php

namespace App\Http\Controllers;

class UserController
{
    public function index()
    {
        return json_encode([
            ['name' => 'Simon'], 
            ['name' => 'Rivalani'],
            ['name' => 'Almina']]
        );
    }
}