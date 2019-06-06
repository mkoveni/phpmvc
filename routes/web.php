<?php

Router::get('/home', [\App\Http\Controllers\HomeController::class, 'index']);

Router::get('/welcome', [\App\Http\Controllers\HomeController::class, 'welcome']);

Router::get('/', [\App\Http\Controllers\HomeController::class, 'index']);