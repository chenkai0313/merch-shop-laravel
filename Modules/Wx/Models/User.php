<?php
/**
 * 用户表
 * Author: CK
 * Date: 2018/1/21
 */

namespace Modules\Wx\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{


    protected $table = 'user';

    protected $primaryKey = 'user_id';

    protected $fillable = array('openid', 'nick_name', 'sex', 'mobile', 'city', 'province', 'avatarUrl', 'user_account', 'ip', 'token', 'type');


    /**
     * 用户的添加
     * @return array
     */
    public static function userAdd($params)
    {
        $arr = ['openid', 'nick_name', 'sex', 'mobile', 'city', 'province', 'avatarUrl', 'user_account', 'ip', 'token', 'type'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return User::create($data);
    }

    /**
     * 用户的列表
     * @return array
     */
    public static function userList($params)
    {
        $offset = ($params['page'] - 1) * $params['limit'];
        $data = User::orderBy('created_at', 'desc')
            ->where(function ($query) use ($params) {
                if (!is_null($params['keyword'])) {
                    return $query->where('nick_name', 'like', '%' . $params['keyword'] . '%');
                }
            })
            ->where('type', $params['type'])
            ->skip($offset)
            ->take($params['limit'])
            ->get()
            ->toArray();
        return $data;
    }

    public static function userListCount($params)
    {
        $data = User::orderBy('created_at', 'desc')
            ->where(function ($query) use ($params) {
                if (!is_null($params['keyword'])) {
                    return $query->where('nick_name', 'like', '%' . $params['keyword'] . '%');
                }
            })
            ->where('type', $params['type'])
            ->get()
            ->toArray();
        return count($data);
    }

    /**
     * 用户的更新
     * @return array
     */
    public static function userEdit($params)
    {
        $arr = ['openid', 'nick_name', 'sex', 'mobile', 'city', 'province', 'avatarUrl', 'user_account', 'ip', 'token'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return User::where('openid', $params['openid'])->update($data);
    }

    /**
     * 用户的更新(后台)
     * @return array
     */
    public static function userEditBack($params)
    {
        $arr = ['openid', 'nick_name', 'sex', 'mobile', 'city', 'province', 'avatarUrl', 'user_account', 'ip', 'token'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return User::where('user_id', $params['user_id'])->update($data);
    }

    /**
     * 用户更新token
     * @return array
     */
    public static function userEditToken($params)
    {
        $data['token'] = $params['token'];
        return User::where('user_id', $params['user_id'])->update($data);
    }

    /**
     * 用户的是否存在
     * @return array
     */
    public static function userExist($openid)
    {
        return User::where('openid', $openid)->first();
    }

    /**
     * 用户的详情
     * @return array
     */
    public static function userDetail($params)
    {
        return User::where('user_id', $params['user_id'])->first();
    }

    /**
     * 用户的列表(所有模拟用户)
     * @return array
     */
    public static function userListAll($params)
    {
        return User::where('type', 1)
            ->select('nick_name', 'avatarUrl', 'user_id')
            ->where(function ($query) use ($params) {
                if (!empty($params['nick_name'])) {
                    return $query->where('nick_name', 'like', '%' . $params['nick_name'] . '%');
                }
            })
            ->get()
            ->toArray();
    }

    /**
     * 用户的删除(后台)
     * @return array
     */
    public static function userDeleteBack($params)
    {
        return User::where('type', 0)->where('user_id', $params['user_id'])->delete();
    }
}