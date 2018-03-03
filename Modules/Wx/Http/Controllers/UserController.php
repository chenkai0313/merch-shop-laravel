<?php
/**
 * 用户
 * Author: CK
 * Date: 2018/1/20
 */

namespace Modules\Wx\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class UserController extends Controller
{
    /**
     * 用户初始化登陆操作
     * @return array
     */
    public function userInit(Request $request)
    {
        $params = $request->all();
        $params['ip'] = $request->getClientIp();
        $result = \UserService::userInit($params);
        return $result;
    }

}