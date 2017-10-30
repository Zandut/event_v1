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

$app->get('/get_events', 'EventController@get_events');
$app->post('/register', 'UserController@register');
$app->post('/login', 'UserController@login');

$app->group(['middleware' => 'auth'], function($app)
{
    $app->post('/create_user', 'UserController@create_user');
    $app->post('/create_event', 'EventController@create_event');

});
