<?php
/**
 * 管理员表
 * Author: ck
 * Date: 2018/2/25
 */

namespace Modules\Backend\Models;

use function foo\func;
//use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class Admin extends Authenticatable
{
    use EntrustUserTrait {
        restore as private restoreA;
    }

//    use SoftDeletes {
//        restore as private restoreB;
//    }

    protected $table = 'admins';

    protected $primaryKey = 'admin_id';

    protected $fillable = array('admin_name', 'admin_nick', 'admin_password', 'is_super', 'remark', 'remember_token');

//    protected $dates = ['deleted_at'];

    /**
     * 解决 EntrustUserTrait 和 SoftDeletes 冲突
     */
    public function restore()
    {
        $this->restoreA();
        $this->restoreB();
    }


    /**
     * 管理员  编辑
     * @params int $admin_id 管理员ID
     * @params string $admin_password 密码
     * @return array
     */
    public static function adminEdit($params)
    {
        $admin = Admin::find($params['admin_id']);
        if (isset($params['admin_password']) && !empty($params['admin_password'])) {
            $admin->admin_password = bcrypt($params['admin_password']);
        }
        $admin->admin_nick = $params['admin_nick'];
        $admin->admin_name = $params['admin_name'];
        $result = $admin->save();
        return $result;
    }

    /**
     * 管理员  添加
     * @params string $admin_name 账号
     * @params string $admin_password 密码
     * @return array
     */
    public static function adminAdd($params)
    {
        $params['admin_password'] = $params['admin_password'] ? bcrypt($params['admin_password']) : bcrypt('111111');
        $res = Admin::create($params);
        return $res->admin_id;
    }

    /**
     * 管理员  详情
     * @param int $admin_id 管理员ID
     * @return array
     */
    public static function adminDetail($admin_id)
    {
        $result = Admin::select(['admin_id', 'admin_name', 'admin_nick', 'created_at'])->where('admin_id', $admin_id)->first();

        return $result;
    }

    /**
     * 管理员  账号是否唯一
     * @params int $admin_id 管理员ID
     * @return array
     */
    public static function adminExist($admin_name, $admin_id = '')
    {
        $result = Admin::where('admin_name', $admin_name)->where(function ($query) use ($admin_id) {
            $query->where('admin_id', '!=', $admin_id);
        })->count();
        return $result;
    }

    /**
     * 管理员  根据admin_name获取用户数据
     * @params string $admin_name 管理员账号
     * @params string $admin_password 管理员密码
     * @return bool
     */
    public static function adminInfo($admin_name)
    {
        $result = Admin::where(['admin_name' => $admin_name])->select('admin_id', 'admin_name', 'admin_nick', 'admin_password', 'is_super')->first();
        return $result;
    }

    public static function adminInfoById($admin_id)
    {
        $result = Admin::where('admin_id', $admin_id)
            ->select(['admin_nick', 'city'])
            ->first();
        return $result;
    }

    /**
     * 用户密码修改
     * @params int $admin_id 用户id
     * @params string $admin_password 用户密码
     * @params string $admin_password_change 用户修改后的密码
     * @return array
     */
    public static function adminPasswordEdit($params)
    {
        $result = Admin::where('admin_id', $params['admin_id'])->update($params);
        return $result;
    }


}