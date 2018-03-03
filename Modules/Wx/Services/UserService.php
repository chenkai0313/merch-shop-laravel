<?php
/**
 * 用户
 * Author: ck
 * Date: 2018/1/20
 */

namespace Modules\Wx\Services;

use Modules\Wx\Http\Controllers\WxController;
use Modules\Wx\Models\User;

class UserService
{
    /**
     * 用户初始化登陆操作
     * @return array
     */
    public function userInit($params)
    {
        if (isset($params['code'])) {
            $getOpenid = new WxController();
            $openid = $getOpenid->getOpenid($params);
            $params['openid'] = $openid['openid'];
            $user = User::userExist($openid['openid']);
            if ($user) {
                return ['code' => 1, 'data' => self::userDetail($params)];
            }
            #新用户添加
            $params['token'] = getRandomkeys();
            $res = self::userAdd($params);
            if ($res) {
                return ['code' => 1, 'data' => $res];
            }
        } elseif (isset($params['user_id'])) {
            $token['token'] = getRandomkeys();
            $token['user_id'] = $params['user_id'];
            User::userEditToken($token);
            return ['code' => 1, 'data' => User::userDetail($params)];
        } else {
            return ['code' => '90002', 'msg' => '非法操作'];
        }
    }

    /**
     * 用户的添加
     * @return array
     */

    public function userAdd($params)
    {
        $validator = \Validator::make($params, [
            'openid' => 'required|unique:user',
        ], [
            'integer' => ':attribute必须为整数',
            'required' => ':attribute必填',
            'unique' => ':attribute已经存在',
            'min' => ':attribute最少为11位',
        ], [
            'openid' => 'openid',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $data = User::userAdd($params);
        if ($data) {
            $result['code'] = 1;
            $result['data'] = $data;
        } else {
            $result['code'] = 90002;
            $result['msg'] = "添加失败";
        }
        return $result;
    }

    /**
     * 用户的添加(后台)
     * @return array
     */
    public function userAddBack($params)
    {
        $validator = \Validator::make($params, [
            'nick_name' => 'required',
            'avatarUrl' => 'required',
        ], [
            'integer' => ':attribute必须为整数',
            'required' => ':attribute必填',
            'unique' => ':attribute已经存在',
            'min' => ':attribute最少为11位',
        ], [
            'nick_name' => '昵称',
            'avatarUrl' => '头像',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $data = User::userAdd($params);
        if ($data) {
            $result['code'] = 1;
            $result['msg'] = '添加成功';
        } else {
            $result['code'] = 90002;
            $result['msg'] = "添加失败";
        }
        return $result;
    }

    /**
     * 用户的编辑
     * @return array
     */

    public function userEdit($params)
    {
        if (!isset($params['openid'])) {
            return ['code' => 90002, 'msg' => 'openid不能为空'];
        }
        $validator = \Validator::make($params, [
            'openid' => 'required',
        ], [
            'integer' => ':attribute必须为整数',
            'required' => ':attribute必填',
            'unique' => ':attribute已经存在',
            'min' => ':attribute最少为11位',
        ], [
            'openid' => 'openid',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $data = User::userEdit($params);
        if ($data) {
            $result['code'] = 1;
            $result['msg'] = "编辑成功";
        } else {
            $result['code'] = 90002;
            $result['msg'] = "编辑失败";
        }
        return $result;
    }

    /**
     * 用户的编辑(后台)
     * @return array
     */

    public function userEditBack($params)
    {
        if (!isset($params['user_id'])) {
            return ['code' => 90002, 'msg' => 'user_id不能为空'];
        }
        $validator = \Validator::make($params, [
            'nick_name' => 'required',
            'avatarUrl' => 'required',
        ], [
            'integer' => ':attribute必须为整数',
            'required' => ':attribute必填',
            'unique' => ':attribute已经存在',
            'min' => ':attribute最少为11位',
        ], [
            'nick_name' => '昵称',
            'avatarUrl' => '头像',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $data = User::userEditBack($params);
        if ($data) {
            $result['code'] = 1;
            $result['msg'] = "编辑成功";
        } else {
            $result['code'] = 90002;
            $result['msg'] = "编辑失败";
        }
        return $result;
    }

    /**
     * 用户的详情
     * @return array
     */

    public function userDetail($params)
    {
        if (!isset($params['openid'])) {
            return ['code' => 90002, 'msg' => 'openid不能为空'];
        }
        $token['token'] = getRandomkeys();
        $token['openid'] = $params['openid'];
        User::userEdit($token);
        $data = User::userExist($params);
        if ($data) {
            $result['code'] = 1;
            $result['data'] = $data;
        } else {
            $result['code'] = 90002;
            $result['msg'] = "查询失败";
        }
        return $result;
    }

    /**
     * 用户的详情(后台)
     * @return array
     */

    public function userDetailBack($params)
    {
        if (!isset($params['user_id'])) {
            return ['code' => 90002, 'msg' => 'user_id不能为空'];
        }
        $data = User::userDetail($params);
        if ($data) {
            $result['code'] = 1;
            $result['data'] = $data;
        } else {
            $result['code'] = 90002;
            $result['msg'] = "查询失败";
        }
        return $result;
    }

    /**
     * 用户的列表
     * @return array
     */

    public function userList($params)
    {
        if (!isset($params['type'])) {
            return ['code' => 90002, 'msg' => '类型选择'];
        }
        $params['limit'] = isset($params['limit']) ? $params['limit'] : 20;
        $params['page'] = isset($params['page']) ? $params['page'] : 1;
        $params['keyword'] = isset($params['keyword']) ? $params['keyword'] : null;
        $data['list'] = User::userList($params);
        $data['count'] = User::userListCount($params);
        $data['page'] = $params['page'];
        $data['limit'] = $params['limit'];
        return ['code' => 1, 'data' => $data];
    }

    /**
     * 用户的列表(所有模拟用户)
     * @return array
     */
    public function userListAll($params)
    {
        $params['nick_name'] = isset($params['nick_name']) ? $params['nick_name'] : '';
        $data = User::userListAll($params);
        if ($data) {
            return ['code' => 1, 'data' => $data];
        }
        return ['code' => 90002, 'msg' => '查询失败'];
    }

    /**
     * 用户的删除（后台）
     * @return array
     */
    public function userDeleteBack($params)
    {
        if (!isset($params['user_id'])) {
            return ['code' => 90002, 'msg' => 'id必填'];
        }
        $res = User::userDeleteBack($params);
        if ($res) {
            return ['code' => 1, 'msg' => '删除成功'];
        }
        return ['code' => 90002, 'msg' => '删除失败'];
    }

}
