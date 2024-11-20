<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['middleware' => 'auth_api'], function () {
  Route::get('/product', 'API\ProductController@list_product');
  Route::put('/product', 'API\ProductController@update_product');
  Route::delete('/product', 'API\ProductController@delete_product');


  Route::post('/product/serial-check', 'API\ProductController@serial_check');
  Route::post('/product/serial/add', 'API\ProductController@serial_add');
});
