<?php

use App\Repositories\UserRepository;
use App\Services\UserService;

/*
|--------------------------------------------------------------------------
| Service registry
|--------------------------------------------------------------------------
|
| Services here will be auto-binded to container on bootstrap based on configurations below
|
*/
return [
    UserService::class => [
        UserRepository::class,
    ],
];
