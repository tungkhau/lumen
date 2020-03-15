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
    echo phpinfo();

});
//Group 1
$router->post('/create_user', 'UserController@create');
$router->patch('/reset_user_password', 'UserController@reset_password');
$router->patch('/deactivate_user', 'UserController@deactivate');
$router->patch('/reactivated_user', 'UserController@reactivate');
$router->patch('/change_user_workplace', 'UserController@change_workplace');
//
//$router->post('/register_device, DeviceController@register');
//$router->delete('/delete_device, DeviceController@delete');
//
//$router->post('/create_workplace','WorkplaceController@create');
//$router->delete('/delete_workplace, WorkplaceController@delete');
//
////Group 9
//$router->post('/login_desktop', 'AuthController@login_desktop');
//$router->post('/login_mobile', 'AuthController@login_mobile');
//$router->post('/logout','AuthController@logout');
//$router->post('/change_password','AuthController@change_password');


