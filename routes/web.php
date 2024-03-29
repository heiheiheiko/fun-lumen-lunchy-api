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

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    // orders
    $router->get('/orders', 'V1\OrdersController@index');
    $router->post('/orders', 'V1\OrdersController@create');
    $router->get('/orders/{id}', 'V1\OrdersController@show');
    $router->put('/orders/{id}', 'V1\OrdersController@update');
    $router->delete('/orders/{id}', 'V1\OrdersController@destroy');

    // users
    $router->post('/users', 'V1\UsersController@create');
    $router->post('/users/authenticate', 'V1\UsersController@authenticate');
    $router->get('/users/current', 'V1\UsersController@current');
});
