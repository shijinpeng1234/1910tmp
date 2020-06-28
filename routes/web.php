<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
//测试一下
Route::get('/test/hello','TestController@hello');
Route::get('/info','TestController@info');//phpinfo

//注册
Route::get('/get/user/reg','TestController@reg');
Route::post('/get/user/regdo','TestController@regdo');

//登录
Route::get('/get/user/login','TestController@login');
Route::post('/get/user/logindo','TestController@logindo');

//用户中心
Route::get('/get/user/center','TestController@center');

//商品
Route::get('/goods/detail','Goods\GoodsController@detail'); //商品详情

//API
Route::any('/api/user/reg','Api\UserController@reg');//注册
Route::any('/api/user/login','Api\UserController@login');//登录
Route::any('/api/user/center','Api\UserController@center')->middleware('check.pri');//个人中心
Route::any('/api/my/orders','Api\UserController@orders')->middleware('check.pri');//我的订单
Route::any('/api/my/cart','Api\UserController@cart')->middleware('check.pri','access.filter');//我的购物车