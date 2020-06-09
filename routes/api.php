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
| 'index', 'create', 'store', 'show', 'edit', 'update', 'destroy'
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
        'prefix' => 'v0/admin',
        'namespace' => 'Api\V0',
        'middleware' => 'auth:admin-api'
    ],
    function () {
        Route::get('refresh-token', 'AdminController@refreshToken');

        Route::get('profile', 'AdminController@profile');
        Route::post('profile', 'AdminController@updateProfile');
        Route::resource('admins', 'AdminController')->only([
            'index', 'create', 'show', 'edit'
        ])->middleware(['role:root|admin']);
        Route::post('admins', 'AdminController@store')->middleware(['role_or_permission:root|admins.store']);
        Route::put('admins/{admin}', 'AdminController@update')->middleware(['role_or_permission:root|admins.update']);
        Route::delete('admins/{admin}', 'AdminController@destroy')->middleware(['role_or_permission:root|admins.destroy']);

        Route::resource('roles', 'RoleController')->only([
            'index', 'create', 'show', 'edit'
        ])->middleware(['role:root|admin']);
        Route::post('roles', 'RoleController@store')->middleware(['role_or_permission:root|roles.store']);
        Route::put('roles/{role}', 'RoleController@update')->middleware(['role_or_permission:root|roles.update']);
        Route::delete('roles/{role}', 'RoleController@destroy')->middleware(['role_or_permission:root|roles.destroy']);

        Route::resource('permissions', 'PermissionController')->only([
            'index', 'create', 'show', 'edit'
        ])->middleware(['role:root|admin']);
        Route::post('permissions', 'PermissionController@store')->middleware(['role_or_permission:root|permissions.store']);
        Route::put('permissions/{permission}', 'PermissionController@update')->middleware(['role_or_permission:root|permissions.update']);
        Route::delete('permissions/{permission}', 'PermissionController@destroy')->middleware(['role_or_permission:root|permissions.destroy']);

    }
);
