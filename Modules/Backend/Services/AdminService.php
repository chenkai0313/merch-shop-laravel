<?php

/**
 * 管理员模块
 * Author: ck
 * Date: 2018/2/25
 */

namespace Modules\Backend\Services;

use Modules\Backend\Models\Admin;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Session;

class AdminService
{
    /**
     * 管理员  添加
     * @params string $admin_name 账号
     * @params string $admin_password 密码
     * @return array
     */
    public function adminAdd($params)
    {
        $validator = \Validator::make($params, [
            'admin_name' => 'required|unique:admins|min:18|max:18',
            'admin_password' => array('regex:/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{8,16}$/', 'required', 'same:confirm_password'),
            'confirm_password' => array('regex:/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{8,16}$/', 'required', 'same:admin_password'),
        ], [
            'integer' => ':attribute必须为整数',
            'required' => ':attribute必填',
            'regex' => ':attribute需要8-16位字母和数字',
            'same' => '密码和确认密码不一致',
            'unique' => ':attribute已被注册',
            'min' => ':attribute最少为18位',
            'max' => ':attribute最多为18位'
        ], [
            'admin_name' => '账号',
            'admin_password' => '密码',
            'confirm_password' => '确认密码',
        ]);
        if ($validator->passes()) {
            if (!Admin::adminExist($params['admin_name'])) {
                DB::beginTransaction();
                $res1 = Admin::adminAdd($params);
                if ($res1) {
                    DB::commit();
                    $result['code'] = 1;
                    $result['msg'] = '添加成功';
                } else {
                    DB::rollback();
                    $result['code'] = 10001;
                    $result['msg'] = '添加用户失败';
                }
            } else {
                $result['code'] = 10004;
                $result['msg'] = '该管理账号已存在';
            }
        } else {
            $result['code'] = 90002;
            $result['msg'] = $validator->messages()->first();
        }

        return $result;
    }

    /**
     * 管理员  编辑
     * @params int $admin_id 管理员ID
     * @params string $admin_password 密码
     * @return array
     */
    public function adminEdit($params)
    {
        $validator = \Validator::make($params, [
            'admin_id' => 'required',
            'admin_name' => 'required',
            'admin_nick' => 'required',
        ], [
            'required' => ':attribute为必填项',
            'max' => ':attribute长度不符合要求',
            'unique' => ':attribute必须唯一'
        ], [
            'admin_id' => '管理员id',
            'admin_name' => '帐号',
            'admin_nick' => '昵称',
        ]);
        if (!$validator->passes()) {
            $result['code'] = 90002;
            $result['msg'] = $validator->messages()->first();
            return $result;
        }
        DB::beginTransaction();

        $res1 = Admin::adminEdit($params);
        if ($res1 != false) {
            DB::commit();
            $result['code'] = 1;
            $result['msg'] = '编辑成功';
        } else {
            DB::rollback();
            $result['code'] = 10002;
            $result['msg'] = '编辑失败';
        }

        return $result;
    }

    /**
     * 管理员  详情
     * @params int $admin_id 管理员ID
     * @return array
     */
    public function adminDetail($admin_id)
    {
        $res = Admin::where('admin_id', $admin_id)->select('admin_id', 'admin_name', 'admin_nick')->first();
        $result['data']['admin_id'] = $res['admin_id'];
        $result['data']['admin_name'] = $res['admin_name'];
        $result['code'] = 1;
        return $result;
    }

    /**
     * 管理员  登录
     * @params string $admin_name 管理员账号
     * @params string $admin_password 管理员密码
     * @return array
     */
    public function adminLogin($params)
    {
        $validator = \Validator::make($params, [
            'admin_name' => 'required',
            'admin_password' => 'required',
            'code' => 'required',
        ], [
            'required' => ':attribute为必填项',
        ], [
            'admin_name' => '账号',
            'admin_password' => '密码',
            'code' => '验证码',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        if (is_null($params['captcha'])) {
            return ["code" => 10000, "msg" => "验证码已过期,请重新获取"];
        } elseif (!is_null($params['captcha'])) {
            if ($params['captcha'] !== $params['code']) {
                return ["code" => 10000, "msg" => "验证码填写错误"];
            }
        }
        $admin_info = Admin::adminInfo($params['admin_name']);
        if ($admin_info) {
            if (password_verify($params['admin_password'], $admin_info['admin_password'])) {
                $result['code'] = 1;
                $result['msg'] = '登录成功';
                $customClaim = [
                    'from' => 'admin',
                    'admin_id' => $admin_info['admin_id'],
                    'admin_name' => $admin_info['admin_name'],
                    'is_super' => $admin_info['is_super'],
                ];
                $token = JWTAuth::fromUser($admin_info, $customClaim);
                $result['data']['token'] = $token;
                $result['data']['admin_name'] = $admin_info['admin_name'];
                $result['data']['admin_id'] = $admin_info['admin_id'];
            } else {
                $result['code'] = 10005;
                $result['msg'] = '账号密码不正确';
            }

        } else {
            $result['code'] = 10006;
            $result['msg'] = '该账号不存在或已删除';
        }
        return $result;
    }


    /**
     * 用户密码修改
     * @params int $admin_id 用户id
     * @params string $admin_password 用户密码
     * @params string $admin_password_change 用户修改后的密码
     * @return array
     */
    public function adminChangePassword($params)
    {
        if (!isset($params['admin_password_change'])) {
            return ['code' => 90002, 'msg' => '新密码不能为空'];
        }
        if (!isset($params['admin_password'])) {
            return ['code' => 90002, 'msg' => '旧密码不能为空'];
        }
        if (!isset($params['admin_id'])) {
            return ['code' => 90002, 'msg' => 'id必填'];
        }
        $admin = Admin::find($params['admin_id']);
        if (!password_verify($params['admin_password'], $admin['admin_password'])) {
            return ['code' => 10005, 'msg' => '原密码输入错误'];
        } else {
            $data = [
                'admin_id' => $params['admin_id'],
                'admin_password' => bcrypt($params['admin_password_change']),
            ];
            $res = Admin::adminPasswordEdit($data);
            if ($res) {
                return ['code' => 1, 'msg' => '修改成功'];
            } else {
                return ['code' => 10008, 'msg' => '修改密码失败'];
            }
        }
    }
}
