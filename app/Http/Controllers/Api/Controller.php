<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as BaseController;
use App\Models\RoleUser;
use Dcat\Admin\Models\Role;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    protected $guard = 'home';

    protected $isNeedLogin = true;

    public $exceptLogin = [];

    protected $originExceptLogin = [
        'UserController/login'
    ];
    public    $currentControllerAndFunction;

    public $user;


    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,OPTIONS');
        // 这里额外注意了：官方文档样例中只除外了『login』
        // 这样的结果是，token 只能在有效期以内进行刷新，过期无法刷新
        // 如果把 refresh 也放进去，token 即使过期但仍在刷新期以内也可刷新
        // 不过刷新一次作废
        $this->currentControllerAndFunction = $this->getControllerAndFunction();
        if ($this->isNeedLogin && !in_array($this->currentControllerAndFunction, array_merge($this->originExceptLogin, $this->exceptLogin))) {
            //$this->middleware('auth.jwt', ['except' => array_merge(['login'], $this->exceptLoginMethod)]);
            $user = Auth::guard($this->guard)->user();
            if ($user) {
                $user['role_ids'] = RoleUser::query()->where('user_id', $user['id'])->pluck('role_id')->toArray();
                $this->user       = $user;
            } else {
                throw new AuthorizationException('Unauthorized');
            }
        }
        // 另外关于上面的中间件，官方文档写的是『auth:api』
        // 但是我推荐用 『jwt.auth』，效果是一样的，但是有更加丰富的报错信息返回
    }

    /**
     * @return array
     * 获取控制器和方法名
     */
    function getControllerAndFunction()
    {
        $action = \Route::current()->getActionName();
        list($class, $method) = explode('@', $action);
        $class = substr(strrchr($class, '\\'), 1);
        return $class . '/' . $method;
    }

    /**
     * 成功返回
     * @param $data
     * @param string $msg
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data = [], $msg = 'success')
    {
        $this->parseNull($data);
        $result = [
            "code" => 200,
            "msg"  => $msg,
            "data" => $data,
        ];
        return response()->json($result);
    }

    /**
     * 失败返回.
     *
     * @param string $code
     * @param array $data
     * @param string $msg
     *
     * @return mixed
     */
    public function error($code = "422", $msg = "error", $data = [])
    {
        $result = [
            "code" => $code,
            "msg"  => $msg,
            "data" => $data,
        ];
        return response()->json($result);
    }

    /**
     * 如果返回的数据中有 null 则那其值修改为空 （安卓和IOS 对null型的数据不友好，会报错）
     * @param $data
     */
    private function parseNull(&$data)
    {
        if (is_array($data)) {
            foreach ($data as &$v) {
                $this->parseNull($v);
            }
        } else {
            if (null === $data) {
                $data = "";
            }
        }
    }
}
