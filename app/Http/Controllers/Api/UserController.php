<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\FormRequest;
use App\Http\Requests\Api\UserRequest;
use App\Http\Service\HomeUserService;
use App\Models\HomeUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserRequest $request)
    {
        $this->isNeedLogin = false;
        $posts             = $request->all();
        $exist             = HomeUsers::query()->where('username', $posts['username'])->exists();
        if (!$exist) {
            return $this->error('10000', '手机号不存在！');
        }
        if (!$token = Auth::guard($this->guard)->attempt($posts)) {
            //return response()->json(['error' => 'Unauthorized'], 401);
            return $this->error('10001', '密码错误！');
        }
        return $this->success([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => Auth::guard($this->guard)->factory()->getTTL() * 60
        ]);
    }

    /**
     * 刷新token
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken()
    {
        $token = Auth::guard($this->guard)->refresh();
        return $this->success([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => Auth::guard($this->guard)->factory()->getTTL() * 60
        ]);
    }

    /**
     * 登出
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::guard($this->guard)->refresh();
        return $this->success();
    }

    /**
     * 用户信息
     */
    public function userInfo(FormRequest $request)
    {
        $user      = Auth::guard($this->guard)->user();
        $userCount = HomeUserService::count($user['id']);
        return $this->success([
            'user'       => $user,
            'user_count' => $userCount
        ]);
    }

    /**
     * 重置密码
     */
    public function resetPassword(FormRequest $request)
    {
        $input    = $request->all();
        $rules    = [
            'username'     => 'required',
            'password_old' => 'required',
            'password_new' => 'required',

        ];
        $messages = [
            'username.required'     => '用户名不能为空',
            'password_old.required' => '旧密码不能为空',
            'password_new.required' => '密码不能为空',
        ];

        $validator = Validator::make($input, $rules, $messages);
        $error     = $validator->errors()->first();
        if ($error) {
            return $this->error('50000', $error);
        } else {
            $user = Auth::guard($this->guard)->user();
            // 非当前用户操作
            if ($user['username'] !== $input['username']) {
                return $this->error('50000', '非法操作！');
            }
        }

        // TODO 业务操作
        if (!Hash::check($input['password_old'], $user->password)) {
            return $this->error('50000', '旧密码错误！');
        }
        $user->password = bcrypt($input['password_new']);
        $user->save();
        // 退出登录
        Auth::guard($this->guard)->logout();
        return $this->success();
    }
}
