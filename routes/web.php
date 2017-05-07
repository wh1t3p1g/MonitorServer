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

/**
 * user login
 */
// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/logout','Auth\LoginController@logout');

/**
 * home page
 */
Route::get('/home', 'HomeController@index');
Route::get('/','InstallController@checkInstalled');
Route::get('/message/get','HomeController@messageInfo');

/**
 * Global functions
 */
Route::get('/hosts/get','HostsController@getHosts');
Route::post('/hosts/del','HostsController@delete');
Route::get('/hosts','HostsController@index');
Route::post('/configuration/update/all','ConfigurationController@updateConfiguration');
Route::post('/configuration/update/delay','ConfigurationController@updateHostDelay');
Route::get('/configuration/get/date/{id}','ConfigurationController@getDate');
Route::get('/configuration/{id}','ConfigurationController@index');


/**
 * Monitor functions
 */
Route::get('/monitor/tasks','Monitor\TasksController@index');
Route::get('/monitor/tasks/get','Monitor\TasksController@getTasks');
Route::get('/monitor/add','Monitor\TasksController@addPage');
Route::post('/monitor/task/add','Monitor\TasksController@add');
Route::post('/monitor/task/del','Monitor\TasksController@delete');
Route::post('/monitor/task/update/{id?}','Monitor\TasksController@update');
Route::get('/monitor/task/show/{id?}','Monitor\TasksController@updatePage');
Route::post('/monitor/task/toggle','Monitor\TasksController@toggle');
Route::post('/monitor/path','Monitor\TasksController@getSubPath');

Route::get('/monitor/message/task/get','Monitor\MessagesController@get');
Route::get('/monitor/message/show/get/{task_name}','Monitor\MessagesController@getShow');
Route::get('/monitor/message/delete/{id}','Monitor\MessagesController@delete');
Route::get('/monitor/message','Monitor\MessagesController@index');
Route::get('/monitor/message/{task_name}/show','Monitor\MessagesController@show');

/**
 * webshell functions
 */
Route::get('/webshell/add','Webshell\TasksController@addPage');
Route::post('/webshell/add/task','Webshell\TasksController@add');


Route::get('/webshell/tasks/get','Webshell\TasksController@getTasks');
Route::get('/webshell/tasks','Webshell\TasksController@index');
Route::get('/webshell/task/stop/{id}','Webshell\TasksController@stop');
Route::post('/webshell/task/del','Webshell\TasksController@delete');
Route::get('/webshell/task/{id}','Webshell\TaskDetailController@index');


Route::post('/webshell/discover/update','Webshell\DiscoveredController@update');
Route::get('/webshell/discover/update/{id}','Webshell\DiscoveredController@updatePage');
Route::get('/webshell/discover/delete/{id}','Webshell\DiscoveredController@delete');
Route::get('/webshell/discover/detail/{id}','Webshell\TaskDetailController@index');
Route::get('/webshell/discover/history','Webshell\DiscoveredController@history');
Route::get('/webshell/discover/whitelist','Webshell\DiscoveredController@whitelist');
Route::get('/webshell/discover/get/status/{status}','Webshell\DiscoveredController@getWebshellByStatus');//json
Route::get('/webshell/discover/get/{id}','Webshell\DiscoveredController@getWebshells');//json
Route::get('/webshell/discover/{id}/show','Webshell\DiscoveredController@show');
Route::get('/webshell/discover','Webshell\DiscoveredController@index');


Route::get('/webshell/check/{id}/change/{status}','Webshell\CheckController@changeStatus');
Route::get('/webshell/check/{id}','Webshell\CheckController@index');

Route::get("/webshell/rules/get","Webshell\RulesController@getRules");
Route::post("/webshell/rules/local/add","Webshell\RulesController@addLocalSource");
Route::post("/webshell/rules/remote/add","Webshell\RulesController@addRemoteSource");
Route::get('/webshell/rules','Webshell\RulesController@index');

/**
 * file options
 */
Route::get('/download/{filename}','File\DownloadController@index');
Route::get('/upload','File\UploadController@index');
Route::post('/upload/up','File\UploadController@up');

/**
 * user functions
 */
Route::get('/users/delete/{id}','Management\UsersController@delete');
Route::post('/users/update/{id}','Management\UsersController@update');
Route::post('/users/add','Management\UsersController@add');
Route::get('/users/get','Management\UsersController@getUsers');
Route::get('/users','Management\UsersController@index');

/**
 * Api
 */
Route::post('/api/heartbeat','Api\HeartBeatController@index');
Route::get('/api/test','Api\HeartBeatController@test');
Route::post('/api/messages/add/{ip}','Api\MessagesController@add');
Route::post('/api/messages/scan/{ip}','Api\MessagesController@scanMessage');
