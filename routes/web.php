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

$router->post  ('/json', 'TongkangController@json');

// Auth
$router->post(
    'auth/login',
    [
       'uses' => 'AuthController@authenticate'
    ]
);

$router->group(
    ['middleware' => 'jwt.auth'],
    function() use ($router) {
        $router->get('users', function() {
            $users = \App\User::all();
            return response()->json($users);
        });
    }
);

// Prefix /tongkang/
/*
Note :
1. Tambahi Search bisa isikan dari body
2. Authentication Barier Token JWT (v)
3. Mapping Postman (add collection (v)) - base url
4. Passport js
5. Secret key
6. CORS
7. CSRF
8. Nampilin data sesuai keinginan
9. Buat format JSON
10. Coba kalo input table lebih dari satu
11. Lebih dari satu Data
12. Banyak table banyak data 

*/
// $router->group (['middleware' => 'jwt.auth', 'prefix' => 'tongkang'], function () use ($router) {
//   $router->post  ('/json'       , 'TongkangController@json');
// $router->get   ('/'           , 'TongkangController@index');
// $router->post  ('/input'      , 'TongkangController@store');
// $router->get   ('/search/{id}', 'TongkangController@show');
// $router->put   ('/update/{id}', 'TongkangController@update');
// $router->delete('/delete/{id}', 'TongkangController@destroy');
// });
