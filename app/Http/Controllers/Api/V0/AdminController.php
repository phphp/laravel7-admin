<?php

namespace App\Http\Controllers\Api\V0;

use Carbon\Carbon;
use App\Models\Admin;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AdminController extends Controller
{
    use AuthenticatesUsers;

    /**
     * 重写登录
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($token = $this->attemptLogin($request)) {
            $this->clearLoginAttempts($request);

            // return $this->guard()->user();
            // return $token;
            return $this->makeToken($this->guard()->user());
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    // 认证用户名数据库字段名称, 同时也是前端参数字段名
    public function username()
    {
        return 'name';
    }

    // 认证用到的守卫
    protected function guard()
    {
        return Auth::guard('admin-api');
    }

    // 执行认证
    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);
        $credentials = array_add($credentials, 'active', '1'); // 添加认证 active
        return $this->guard()->attempt($credentials);
    }

    // 创建 token
    private function makeToken(Admin $admin)
    {

        $access_token = auth('admin-api')
            ->claims([
                // 'exp' => Carbon::now()->addSeconds(20)->timestamp,
                'exp' => Carbon::now()->addDays(1)->timestamp,
                'type' => 'access token' // 不设置这个 type 会默认使用下面定义的 refresh token
            ])
            ->login($admin);
        $refresh_token = auth('admin-api')
            ->claims([
                'exp' => Carbon::now()->addDays(365)->timestamp,
                'type' => 'refresh token'
            ])
            ->login($admin);
        return [
            'token_type' => 'bearer',
            'access_token' => $access_token,
            'access_TTL' => '1 day',
            'refresh_token' => $refresh_token,
            'refresh_TTL' => '365 days',
        ];
    }

    /**
     * refresh token
     */
    function refreshToken() {
        $rs = $this->makeToken($this->guard()->user());
        unset($rs['refresh_token']);
        unset($rs['refresh_TTL']);
        return $rs;
    }

    function test(Request $request) {

        return response()->json(auth()->user());
        // auth()->user()->assignRole('writer');
        // return response()->json(auth()->user()->getPermissionsViaRoles());



        $role = Role::firstOrCreate(['name' => 'writer']);  // 创建角色
        $permission = Permission::firstOrCreate(['name' => 'edit articles']);// 创建权限

        $role->givePermissionTo($permission); // 添加

        sleep(1);

        // $validatedData = $request->validate([
        //     'title' => 'required|unique:posts|max:255',
        //     'body' => 'required',
        // ]);

        // abort(403);
        return response()->json(auth()->user());
    }

    public function index(Request $request)
    {
        $request->validate([
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1|max:100',
        ]);
        $admins = Admin::paginate($request->query('per_page', 15));
        return json($admins);
    }
}
