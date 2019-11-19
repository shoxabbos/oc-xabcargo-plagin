<?php 
use Svetlana\DriverApp\Models\Order;

Route::group(['prefix' => 'api'], function()
{
	Route::get('index', 'svetlana\driverapp\controllers\OrdersApi@index');
});

Route::group(['prefix' => 'api/client'], function()
{
	Route::post('register', 'svetlana\driverapp\controllers\UserApi@register');
	Route::post('login', 'svetlana\driverapp\controllers\UserApi@login');
	Route::get('get-user', 'svetlana\driverapp\controllers\UserApi@getUser')->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');
	Route::post('update-user', 'svetlana\driverapp\controllers\UserApi@updateUser')->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');
	Route::post('password/restore', 'svetlana\driverapp\controllers\UserApi@restorePassword');
	Route::post('password/reset', 'svetlana\driverapp\controllers\UserApi@resetPassword');

});

Route::group([
	'prefix' => 'api/client',
	'middleware' => 'Tymon\JWTAuth\Middleware\GetUserFromToken'
], function()
{
	Route::post('order/create', 'svetlana\driverapp\controllers\OrdersApi@createOrder');
	Route::get('orders/get', 'svetlana\driverapp\controllers\OrdersApi@getMyOrders');
	Route::get('order/get/{id}', 'svetlana\driverapp\controllers\OrdersApi@getOrder');
});