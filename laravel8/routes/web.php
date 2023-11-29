<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\RolepermissionController;

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

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function() {

    Route::resource('roles', RoleController::class);

    Route::resource('users', UserController::class);
    Route::get('users/user-log', [UserController::class, 'user-log'])->name('users.user-log');
    Route::get('users/change-password-history', [UserController::class, 'change-password-history'])->name('users.change-password-history');

    Route::resource('products', ProductController::class);
    Route::resource('modules', ModuleController::class);
    Route::resource('rolepermissions', RolepermissionController::class);    

    Route::resource('menus', ProductController::class);
});
