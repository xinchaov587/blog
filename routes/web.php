<?php

// 项目首页-渲染
Route::get('/', 'StaticPagesController@home')->name('home');
// 帮助页-渲染
Route::get('/help', 'StaticPagesController@help')->name('help');
// 关于页-渲染
Route::get('/about', 'StaticPagesController@about')->name('about');

// 用户注册页-渲染
Route::get('/signup', 'UsersController@create')->name('signup');
// 用户模块
Route::resource('/users', 'UsersController');   //, ['only' => 'show', 'create', 'store']
// 用户登录-渲染
Route::get('/login', 'SessionsController@create')->name('login');
// 用户登录-逻辑
Route::post('/login', 'SessionsController@store')->name('login');
// 用户退出登录
Route::delete('/logout', 'SessionsController@logout')->name('logout');