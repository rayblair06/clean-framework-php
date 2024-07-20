<?php

namespace App\Http\Controllers;

use App\Core\App;
use App\Http\BaseController;
use App\Services\UserService;

/**
 * HomeController handles the request to the application's homepage.
 */
class HomeController extends BaseController
{
    /**
     * Invokable method to handle incoming requests.
     *
     * Retrieves user data from the UserService and passes it to the view.
     *
     * @return mixed The view response.
     */
    public function __invoke()
    {
        $userService = App::resolve(UserService::class);

        $users = $userService->get();

        return view('home.latte', [
            'title' => 'Hello World',
            'users' => $users,
        ]);
    }
}
