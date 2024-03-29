<?php

use Illuminate\Support\Facades\Route;
use Thotam\ThotamAuth\Http\Controllers\UserController;

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

Route::middleware(['web', 'auth', 'CheckAccount', 'CheckHr', 'CheckInfo'])->group(function () {

    //Route Admin
    Route::redirect('admin', '/', 301);
    Route::group(['prefix' => 'admin'], function () {

        //Route quản lý người dùng
        Route::redirect('member', '/', 301);
        Route::group(['prefix' => 'member'], function () {

            Route::get('user',  [UserController::class, 'index'])->name('admin.member.user');
            Route::post('select_hr',  [UserController::class, 'select_hr'])->name('admin.member.select_hr');
        });
    });
});

Route::middleware(['web', 'guest'])->group(function () {
    Route::get('login-stnv',  [UserController::class, 'login_stnv'])->name('login_stnv');
    Route::post('login-stnv',  [UserController::class, 'login_stnv_action']);
});
