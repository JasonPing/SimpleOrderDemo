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

$router->group(['prefix' => 'api'], function () use ($router) {
  $router->post('user-login',  ['uses' => 'UserController@authenticate']);
  $router->post('admin-login',  ['uses' => 'AdminController@authenticate']);
});

$router->group(['prefix' => 'api' , 'middleware' => 'auth'], function () use ($router) {

  $router->post('user',  ['uses' => 'UserController@create']);

  $router->post('checkapikey', ['uses' => 'UserController@checkApiKey']);

  $router->get('orders-users/{id}',  ['uses' => 'OrderController@showUserOrders']);

  $router->get('orders/{id}', ['uses' => 'OrderController@showOneOrder']);

  $router->post('orders', ['uses' => 'OrderController@create']);


});

$router->group(['prefix' => 'api' , 'middleware' => 'adminauth'], function () use ($router) {

  $router->post('admin',  ['uses' => 'AdminController@create']);

  $router->get('orders',  ['uses' => 'OrderController@showAllOrders']);

  $router->get('users',  ['uses' => 'UserController@showAllUsers']);

  $router->post('updateOrderStatus/{id}', ['uses' => 'OrderController@updateOrderStatus']);


});
