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
$api = 'api/v1/';
$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->post( $api . 'login', [
    'uses' => 'AuthController@authenticate'
]);

$router->group(
    ['middleware' => ['auth', 'authorise'] ],
    function () use ($router, $api) {
        $router->get($api . 'users',[
            'uses' => 'UserController@getUsers'
        ]);
        $router->post($api . 'users', [
            'uses' => 'UserController@createNewUser'
        ]);

    }
);
$router->group(
    ['middleware' => ['auth', 'authorise'] ],
    function () use ($router, $api) {
        $router->delete( $api . 'items/{id}', [
            'uses' => 'ItemsController@deleteItemsFromStock'
        ]);
        $router->post( $api . 'items', [
            'uses' => 'ItemsController@addItems'
        ]);
        $router->get( $api . 'items', [
            'uses' => 'ItemsController@getItems'
        ]);
        $router->post( $api . 'items/{id}', [
            'uses' => 'ItemsController@addItemStock'
        ]);
        $router->put( $api . 'items/{id}', [
            'uses' => 'ItemsController@updateItems'
        ]);

        $router->post( $api . 'authors', [
            'uses' => 'AuthorController@createNewAuthor'
        ]);
        $router->get( $api . 'authors', [
            'uses' => 'AuthorController@getAuthors'
        ]);
        $router->get( $api . 'authors/{id}/items', [
            'uses' => 'AuthorController@getItemsByAuthor'
        ]);
    }
);

$router->group(
    ['middleware' => ['auth', 'authorise','authoriseToBorrow'] ],
    function () use($router, $api) {
        $router->post( $api . 'borrowers', [
            'uses' => 'BorrowersController@borrowItems'
        ]);
        $router->post( $api . 'returns', [
            'uses' => 'BorrowersController@returnItems'
        ]);
        $router->get( $api . 'users/{id}/items', [
            'uses' => 'BorrowersController@getItemsBorrowedByUser'
        ]);

    }
);
