<?php
/**
 * 轮播图
 * Author: CK
 * Date: 2018/2/25
 */

namespace Modules\Backend\Models;

use Illuminate\Database\Eloquent\Model;

class Adv extends Model
{

    protected $table = 'adv';

    protected $primaryKey = 'adv_id';

    protected $fillable = array('adv_img', 'adv_title', 'sort', 'is_show');

    /**
     * 轮播图的添加
     * @return array
     */
    public static function advAdd($params)
    {
        $arr = ['adv_img', 'adv_title', 'sort', 'is_show'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return Adv::create($data);
    }

    /**
     * 轮播图的修改
     * @return array
     */
    public static function advEdit($params)
    {
        $arr = ['adv_img', 'adv_title', 'sort', 'is_show'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return Adv::where('adv_id', $params['adv_id'])->update($data);
    }

    /**
     * 轮播图的删除
     * @return array
     */
    public static function advDelete($params)
    {
        return Adv::where('adv_id', $params['adv_id'])->delete();
    }

    /**
     * 轮播图的详情
     * @return array
     */
    public static function advDetail($params)
    {
        return Adv::where('adv_id', $params['adv_id'])->first();
    }

    /**
     * 轮播图的列表
     * @return array
     */
    public static function advList($params)
    {
        $offset = ($params['page'] - 1) * $params['limit'];
        $data = Adv::where(function ($query) use ($params) {
            if (!empty($params['adv_title'])) {
                return $query->where('adv_title', 'like', '%' . $params['adv_title'] . '%');
            }
        })
            ->where(function ($query) use ($params) {
                if (!is_null($params['is_show'])) {
                    return $query->where('is_show', $params['is_show']);
                }
            })
            ->skip($offset)
            ->take($params['limit'])
            ->orderByDesc('sort')
            ->orderByDesc('created_at')
            ->get()
            ->toArray();
        return $data;
    }

    public static function advListCount($params)
    {
        $data = Adv::where(function ($query) use ($params) {
            if (!empty($params['adv_title'])) {
                return $query->where('adv_title', 'like', '%' . $params['adv_title'] . '%');
            }
        })
            ->where(function ($query) use ($params) {
                if (!is_null($params['is_show'])) {
                    return $query->where('is_show', $params['is_show']);
                }
            })
            ->get()
            ->toArray();
        return count($data);
    }
}