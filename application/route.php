<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;
Route::any('/','index/index');
Route::any('/login','index/login');
Route::any('/myself','index/myself');
Route::any('/uppage','index/uppage');
Route::any('/telogin','index/telogin');
Route::any('/admin','index/admin');
Route::any('/upload','index/upload');
Route::any('/delete','index/delete');
Route::any('/checklog','index/checklog');
Route::any('/addClass','index/addClass');
Route::any('/deleteCla','index/deleteCla');
Route::any('/downloadOne','index/downloadOne');
Route::any('/downfile','index/downfile');
Route::any('/downloadAll','index/downloadAll');
Route::any('/deleteStu','index/deleteStu');
Route::any('/register','index/register');
Route::any('/addStu','index/addStu');
Route::any('/teReg','index/teReg');
Route::any('/regist','index/regist');
Route::any('/newuser','index/newuser');
Route::any('/userclass','index/userclass');
Route::any('/stuClass','index/stuClass');
Route::any('/admin/index','admin/index');
Route::any('/Administration','admin/Administration');
Route::any('tologin','admin/login');
Route::any('stuupload','admin/upload');
Route::any('delectMany','admin/delectMany');
