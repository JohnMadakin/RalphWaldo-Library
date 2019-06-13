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
$router->post('login', [
    'uses' => 'AuthController@authenticate'
]);

$router->group(
    ['middleware' => ['auth', 'authorise'] ],
    function () use ($router) {
        $router->get('users',[
            'uses' => 'UserController@getUsers'
        ]);
        $router->post('users', [
            'uses' => 'UserController@createNewUser'
        ]);

    }
);
$router->group(
    ['middleware' => ['auth', 'authorise'] ],
    function () use ($router) {
        $router->delete('items/{id}', [
            'uses' => 'ItemsController@deleteItemsFromStock'
        ]);
        $router->post('items', [
            'uses' => 'ItemsController@addItems'
        ]);
        $router->get('items', [
            'uses' => 'ItemsController@getItems'
        ]);
        $router->post('items/{id}', [
            'uses' => 'ItemsController@addItemStock'
        ]);
        $router->put('items/{id}', [
            'uses' => 'ItemsController@updateItems'
        ]);

        $router->post('authors', [
            'uses' => 'AuthorController@createNewAuthor'
        ]);
        $router->get('authors', [
            'uses' => 'AuthorController@getAuthors'
        ]);
        $router->get('authors/{id}/items', [
            'uses' => 'AuthorController@getItemsByAuthor'
        ]);
    }
);

$router->group(
    ['middleware' => ['auth', 'authorise','authoriseToBorrow'] ],
    function () use($router) {
        $router->post('borrowers', [
            'uses' => 'BorrowersController@borrowItems'
        ]);
        $router->post('returns', [
            'uses' => 'BorrowersController@returnItems'
        ]);
        $router->get('users/{id}/items', [
            'uses' => 'BorrowersController@getItemsBorrowedByUser'
        ]);

    }
);
