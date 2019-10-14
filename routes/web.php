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

$router->post  ('/try', 'TrialController@try');

// Auth
$router->post('auth/login',['uses' => 'AuthController@authenticate']);
$router->post('/register', 'AuthController@register');

// Api
$router->post  ('/api', 'TongkangController@api');
$router->group(['middleware' => 'jwt.auth'],function() use ($router) {

  }
);

// baru
// between and
/*
json-> form
form -> json
validasi table
save dan update digabung jika ada id maka update jika tidak ada berarti save
file base64
Endpoint (url) dipisah (index, store, view)
select -> pagination
config taruh diluar vendor

*/
