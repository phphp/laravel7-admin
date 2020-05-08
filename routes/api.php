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
        // 'middleware' => 'auth:admin-api'
    ],
    function () {
        Route::post('admin/login', 'AdminController@login');

    }
);
