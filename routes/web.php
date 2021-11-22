<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->group(['middleware' => 'auth'], function() use($router){

    //Movie routes
    $router->get('/movies', 'MovieController@getAllMovies');
    $router->get('/movies/{id}', 'MovieController@get');
    $router->get('/movies/{userId}/posts', 'MovieController@getUserMovies');
    $router->post('/movies', 'MovieController@createMovie');
    $router->put('/movies/{id}', 'MovieController@updateMovie');
    $router->delete('/movies/{id}', 'MovieController@deleteMovie');

    //User routes
    $router->get('/users', 'UserController@getAllUsers');
    $router->get('/users/me', 'UserController@getAuthenticatedUser');
    $router->get('/users/{id}', 'UserController@get');
    $router->put('/users/{id}', 'UserController@updateUser');
    $router->delete('/users/{id}', 'UserController@deleteUser');

    //Category routes
    $router->post('/category', 'CategoryController@createCategory');
    $router->put('/category/{id}', 'CategoryController@updateCategory');
    $router->delete('/category/{id}', 'CategoryController@deleteCategory');
    $router->get('/category', 'CategoryController@getAllCategories');

    //Actor routes
    $router->get('/actors', "ActorController@getAllActors");
    $router->get('/actors/{id}', "ActorController@get");
    $router->post('/actors', "ActorController@createActor");
    $router->put('/actors/{id}', "ActorController@editActor");
    $router->delete('/actors/{id}', "ActorController@deleteActor");
});

//create a new user
$router->post('/users', 'UserController@createUser');

//login
$router->post('/auth/login', 'AuthController@login');   
