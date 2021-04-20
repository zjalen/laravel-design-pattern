<?php

use App\Http\Controllers\TController;
use Illuminate\Support\Facades\Route;

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
    return 'Hello, World';
});

// 通配隐性路由，不建议实际项目使用
Route::any('/t/{action}', function ($action) {
    return app()->call([new TController(), $action]);
});

Route::resource('users', 'UserController');
