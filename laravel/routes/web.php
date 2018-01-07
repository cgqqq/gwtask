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
//起始登录页面路由
Route::get('/', function () {
	return view('Index/login');
});
Route::post('user/login','User\UserController@login');

//注册路由
Route::group(['prefix'=>'register'],function(){
	//显示注册页面
	Route::get('display',function(){
		return view('Index/register');
	});
	//执行注册动作
	Route::post('add','User\UserController@add');
});

//回到主页
Route::get('home', function() {
    return view('Home/home');
})->middleware('auth');

//用户相关路由
Route::group(['middleware'=>'auth','namespace'=>'User','prefix'=>'user'],function(){
	Route::get('displayAdd',function(){
		return view('User/displayAdd');
	});

	Route::get('displayAll','UserController@displayAll');

	Route::get('get','UserController@get');

	Route::get('del','UserController@del');

	Route::get('logout', 'UserController@logout');

	Route::get('editPass', 'UserController@editPass');

	Route::get('edit','UserController@edit');

	Route::get('displayInfo','UserController@displayInfo');

	Route::get('displayFollow', 'UserController@displayFollow')->name('displayFollow');

	Route::get('follow', 'UserController@follow');
});

Route::group(['middleware'=>'auth','namespace'=>'Team','prefix'=>'team'],function(){
	Route::get('displayManage','TeamController@displayMine');

	Route::get('displayMine','TeamController@displayMine')->name('displayMyTeam');

	Route::get('displayAll','TeamController@displayAll');

	Route::get('displayAdd',function(){
		return view('Team/displayAdd');
	});

	Route::get('displayOne/{team_name}', 'TeamController@displayOne')->name('displayOneTeam');

	Route::post('add','TeamController@add');

	Route::get('quit','TeamController@quit');

	Route::get('join','TeamController@join');

	Route::get('removeMember','TeamController@removeMember');
});

Auth::routes();


