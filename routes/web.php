<?php

// 项目首页-渲染
Route::get('/', 'StaticPagesController@home')->name('home');
// 帮助页-渲染
Route::get('/help', 'StaticPagesController@help')->name('help');
// 关于页-渲染
Route::get('/about', 'StaticPagesController@about')->name('about');

// 用户注册页-渲染
Route::get('/signup', 'UsersController@create')->name('signup');
// 用户模块-资源
Route::resource('/users', 'UsersController');   //, ['only' => 'show', 'create', 'store', 'edit']
// 用户登录-渲染
Route::get('/login', 'SessionsController@create')->name('login');
// 用户登录-逻辑
Route::post('/login', 'SessionsController@store')->name('login');
// 用户退出登录
Route::delete('/logout', 'SessionsController@logout')->name('logout');

// 用户账号激活
Route::get('signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');

// 用户找回密码
// 显示重置密码的邮箱发送页面-渲染
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// 邮箱发送重设链接-逻辑
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// 密码更新页面-渲染
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// 执行密码更新操作-逻辑
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// 微博发布
Route::resource('statuses', 'StatusesController', ['only' => ['store', 'destroy']]);

// 粉丝相关
Route::get('/users/{user}/followings', 'UsersController@followings')->name('users.followings');
Route::get('/users/{user}/followers', 'UsersController@followers')->name('users.followers');

// 关注 取关
Route::post('/users/followers/{user}', 'FollowersController@store')->name('followers.store');
Route::delete('/users/followers/{user}', 'FollowersController@destroy')->name('followers.destroy');