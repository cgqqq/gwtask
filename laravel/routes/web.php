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
//index页面
Route::get('/', function () {
    return view('Index/index');
});
//登录页面路由
Route::get('login1', function () {
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
	//显示添加页面
	Route::get('displayAdd',function(){
		return view('User/displayAdd');
	});
	//显示所有团队
	Route::get('displayAll','UserController@displayAll');
	
	Route::get('get','UserController@get');

	Route::get('del','UserController@del');

	Route::get('logout', 'UserController@logout');

	Route::get('editPass', 'UserController@editPass');

	Route::get('edit','UserController@edit');

	Route::get('displayInfo','UserController@displayInfo');

	Route::get('displayFollow', 'UserController@displayFollow')->name('displayFollow');

	Route::get('displayFollower', 'UserController@displayFollower')->name('displayFollower');

	Route::get('follow', 'UserController@follow');

	Route::get('displayAllForAdd/{team_name}','UserController@displayAllForAdd')->name('displayAllForAdd');

    Route::any('displaySearchResult', 'UserController@displaySearchResult');

    Route::get('displayOthersInfo/{user_id}','UserController@displayOthersInfo');

    Route::get('displayOthersInfoTeams','UserController@displayOthersInfoTeams')->name('displayOthersInfoTeams');

    Route::any('displayInfoOptions','UserController@displayInfoOptions');

    Route::any('applyJoinTeam','UserController@applyJoinTeam');

    Route::get('displayInfoMailBox','UserController@displayInfoMailBox');

    Route::any('acceptInvitation','UserController@acceptInvitation');

    Route::any('refuseInvitation','UserController@refuseInvitation');

    Route::any('sendMail','UserController@sendMail');

    Route::post('dumpMail','UserController@dumpMail');

    Route::any('displayInfoMailContent/{mail_id}','UserController@displayInfoMailContent');

    Route::any('displayInfoMyMailContent/{mail_id}','UserController@displayInfoMyMailContent');

});
//团队相关路由
Route::group(['middleware'=>'auth','namespace'=>'Team','prefix'=>'team'],function(){
	Route::get('displayManage','TeamController@displayMine');

	Route::get('displayMine/{sort_key}','TeamController@displayMine')->name('displayMyTeam');

	Route::get('displayAll','TeamController@displayAll');

	Route::get('displayAdd',function(){
		return view('Team/displayAdd');
	});

	Route::get('displayOne/{team_name}', 'TeamController@displayOne')->name('displayOneTeam');

	Route::post('add','TeamController@add');

	Route::get('quit','TeamController@quit');

	Route::get('join','TeamController@join');

	Route::get('removeMember','TeamController@removeMember');

	Route::post('displaySearchMine','TeamController@displaySearchMine')->name('SearchMyTeam');

	Route::get('displayMineCre/{sort_key}','TeamController@displayMineCre')->name('displayMyTeamCre');

	Route::post('addTeammates','TeamController@addTeammates');

    Route::any('displaySearchResult', 'TeamController@displaySearchResult')->name('SearchAllTeam');

    Route::any('manageTeamMember','TeamController@manageTeamMember')->name('ManageTeamMember');

    Route::any('manageTeamMemberAdd','TeamController@manageTeamMemberAdd')->name('ManageTeamMemberAdd');

    Route::any('sendInvitation','TeamController@sendInvitation');

    Route::any('applicationManage','TeamController@applicationManage');

    Route::post('createTeamUploading','TeamController@createTeamUploading');

    Route::any('displayOneAuthTasks/{team_id}','TeamController@displayOneAuthTasks')->name('displayOneAuthTasks');

    Route::any('displayOneAuthResources/{team_id}','TeamController@displayOneAuthResources')->name('displayOneAuthResources');

});
//任务路由
Route::group(['middleware'=>'auth','namespace'=>'Task','prefix'=>'task'],function(){
	Route::get('displayAdd/{team_name}','TaskController@displayAdd');

	Route::post('add','TaskController@add');

	Route::any('displayAll','TaskController@displayAll')->name('displayAllTask');

	Route::get('displayAdd/displayAllocateSubTask/{task_id}/{team_id}', 'TaskController@displayAllocateSubTask');

	Route::post('createTransaction','TaskController@createTransaction');

    Route::post('deleteTransaction','TaskController@deleteTransaction');

    Route::get('getTeamUsers','TaskController@getTeamUsers');

    Route::post('allocateSub','TaskController@allocateSub');
});

Auth::routes();


