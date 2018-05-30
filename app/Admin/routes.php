<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    $router->resources([
        '/guess/categorys'  => Guess\CategoryController::class,
        '/guess/games'      => Guess\GuessController::class,
        '/guess/teams'      => Guess\TeamsController::class,
        '/users/member'     => Users\UserController::class,
        '/users/joins'      => Users\UserJoinController::class,
    ]);

});
