<?php
/**
 * 用户
 * Author: CK
 * Date: 2018/2/25
 */

namespace Modules\Backend\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Wx\Services\UserService;

class UserController extends Controller
{
    /**
     * 用户的添加
     * @return array
     */
    public function userAdd(Request $request)
    {
        $params = $request->all();
        $user = new UserService();
        $params['openid'] = 'testuser' . time();
        $params['type'] = 0;
        $result = $user->userAddBack($params);
        return $result;
    }

    /**
     * 用户的编辑
     * @return array
     */
    public function userEdit(Request $request)
    {
        $params = $request->all();
        $user = new UserService();
        $params['type'] = 1;
        $result = $user->userEditBack($params);
        return $result;
    }

    /**
     * 用户的详情
     * @return array
     */
    public function userDetail(Request $request)
    {
        $params = $request->all();
        $user = new UserService();
        $result = $user->userDetailBack($params);
        return $result;
    }

    /**
     * 用户的列表
     * @return array
     */
    public function userList(Request $request)
    {
        $params = $request->all();
        $user = new UserService();
        $result = $user->userList($params);
        return $result;
    }

    /**
     * 用户的删除
     * @return array
     */
    public function userDelete(Request $request)
    {
        $params = $request->all();
        $user = new UserService();
        $params['type'] = 1;
        $result = $user->userDeleteBack($params);
        return $result;
    }

    /**
     * 用户的列表(所有模拟用户)
     * @return array
     */
    public function userListAll(Request $request)
    {
        $params = $request->all();
        $user = new UserService();
        $result = $user->userListAll($params);
        return $result;
    }
}
