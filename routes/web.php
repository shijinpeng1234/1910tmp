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