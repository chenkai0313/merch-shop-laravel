<?php
/**
 *点赞
 * Author: CK
 * Date: 2018/2/25
 */

namespace Modules\Backend\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{

    protected $table = 'like';

    protected $primaryKey = 'like_id';

    protected $fillable = array('prod_id', 'user_id');

    /**
     * 点赞的添加
     * @return array
     */
    public static function likeAdd($params)
    {
        $arr = ['prod_id', 'user_id'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return Like::create($data);
    }
    /**
     * 点赞的是否存在
     * @return array
     */
    public static function likeExist($params)
    {
        return Like::where('prod_id', $params['prod_id'])->where('user_id', $params['user_id'])->first();
    }

    /**
     * 点赞的删除
     * @return array
     */
    public static function likeDelete($params)
    {
        return Like::where('prod_id', $params['prod_id'])->where('user_id', $params['user_id'])->delete();
    }

    /**
     * 点赞的列表
     * @return array
     */
    public static function likeList($params)
    {
        $data = Like::leftJoin('user', 'user.user_id', '=', 'like.user_id')
            ->select('user.avatarUrl','user.user_id')
            ->where('like.prod_id', $params['prod_id'])
            ->orderByDesc('like.created_at')
            ->get();
        return $data;
    }
}