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
use Illuminate\Support\Facades\Route;

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->group(['prefix' => 'v1'], function () use ($router) {
    $router->post('auth/login', 'AuthController@login');
    $router->group(['middleware'=>'auth'], function () use ($router){
        $router->get('authentic/profile', 'AuthController@userInfo');
        $router->post('auth/logout', 'AuthController@logout');
        $router->get('/userList', 'WalletController@userList');
        $router->post('/walletTransfer', 'WalletController@saveWalletData');
        $router->get('/transactionList', 'WalletController@getTransactionList');
        $router->get('/highestTransaction', 'WalletController@highestTransaction');
    });
    

});