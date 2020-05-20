<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
\DB::enableQueryLog();

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// 无需认证
Route::group(
    [
        'prefix' => 'v0',
        'namespace' => 'Api\V0',
    ],
    function () {
        Route::post('admin/login', 'AdminController@login');

    }
);

// 需要管理登录状态
Route::group(
    [
        'prefix' => 'v0',
        'namespace' => 'Api\V0',
        'middleware' => 'auth:admin-api'
    ],
    function () {
        Route::get('admin/test', 'AdminController@test')->middleware(['role:admin']);
        Route::get('admin/refresh-token', 'AdminController@refreshToken');

    }
);
