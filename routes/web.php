<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// API route group
$router->group(['prefix' => 'api'], function () use ($router) {
    // Matches "/api/login
    $router->post('login', 'AuthController@login');
    // LIHAT PROFILE SENDIRI
    $router->get('profile', 'UserController@profile');

    $router->group(['middleware' => 'checkRole:teknisi'], function () use ($router) {
        // LIHAT UKER YANG TERHUBUNG DENGAN USER YANG LOGIN
        $router->get('users', 'UserController@index');
    });

    $router->group(['middleware' => 'checkRole:admin'], function () use ($router) {
        // CRUD USER
        $router->get('admin', 'UserController@listusers');
        $router->post('users', 'UserController@store');
        $router->get('users/{user_id}', 'UserController@show');
        $router->put('users/{user_id}', 'UserController@update');
        $router->delete('users/{user_id}', 'UserController@destroy');
        // CRUD KANWIL
        $router->get('kanwils', 'KanwilController@index');
        $router->post('kanwils', 'KanwilController@store');
        $router->get('kanwils/{kanwil_id}', 'KanwilController@show');
        $router->put('kanwils/{kanwil_id}', 'KanwilController@update');
        $router->delete('kanwils/{kanwil_id}', 'KanwilController@destroy');
        // CRUD UKER
        $router->get('ukers', 'UkerController@index');
        $router->post('ukers', 'UkerController@store');
        $router->get('ukers/{uker_id}', 'UkerController@show');
        $router->put('ukers/{uker_id}', 'UkerController@update');
        $router->delete('ukers/{uker_id}', 'UkerController@destroy');
        //LIHAT UKER BERDASARKAN USER
        $router->get('users/{user_id}/ukers', 'UkerController@indexUkerUser');
        //LIHAT UKER BERDASARKAN KANWIL
        $router->get('kanwils/{kanwil_id}/ukers', 'UkerController@indexUkerKanwil');
    });
});
