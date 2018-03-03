<?php
/**
 * 订单
 * Author: CK
 * Date: 2018/2/25
 */

namespace Modules\Backend\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $table = 'order';

    protected $primaryKey = 'order_id';

    protected $fillable = array('order_sn', 'user_id', 'type', 'status', 'prod_id','prod_price');

    /**
     * 订单的添加
     * @return array
     */
    public static function orderAdd($params)
    {
        $arr = ['order_sn', 'user_id', 'type', 'status', 'prod_id','prod_price'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return Order::create($data);
    }

    /**
     * 订单的编辑
     * @return array
     */
    public static function orderEdit($params)
    {
        $arr = ['order_sn', 'user_id', 'type', 'status', 'prod_id','prod_price'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return Order::where('order_sn', $params['order_sn'])->update($data);
    }

    /**
     * 订单的删除
     * @return array
     */
    public static function orderDelete($params)
    {
        return Order::where('order_sn', $params['order_sn'])->delete();
    }

    /**
     * 订单的详情
     * @return array
     */
    public static function orderDetail($params)
    {
        $data = Order::leftJoin('product', 'product.prod_id', '=', 'order.prod_id')
            ->leftJoin('user', 'user.user_id', '=', 'order.user_id')
            ->select('order.*', 'user.nick_name', 'user.user_id', 'user.avatarUrl', 'product.prod_id','product.prod_type', 'product.prod_title')
            ->first();
        return $data;
    }

    /**
     * 订单的列表
     * @return array
     */
    public static function orderList($params)
    {
        $offset = ($params['page'] - 1) * $params['limit'];
        $data = Order::leftJoin('product', 'product.prod_id', '=', 'order.prod_id')
            ->leftJoin('user', 'user.user_id', '=', 'order.user_id')
            ->select('order.*', 'user.nick_name', 'user.user_id', 'user.avatarUrl', 'product.prod_id','product.prod_type', 'product.prod_title')
            ->where(function ($query) use ($params) {
                if (!is_null($params['order_sn'])) {
                    $query->where('order.order_sn', 'like', '%' . $params['order_sn'] . '%');
                }
            })
            ->where(function ($query) use ($params) {
                if (!is_null($params['status'])) {
                    $query->where('order.status', 'like', '%' . $params['status'] . '%');
                }
            })
            ->where(function ($query) use ($params) {
                if (!is_null($params['prod_title'])) {
                    $query->where('product.prod_title', 'like', '%' . $params['prod_title'] . '%');
                }
            })
            ->where(function ($query) use ($params) {
                if (!is_null($params['nick_name'])) {
                    $query->where('user.nick_name', 'like', '%' . $params['nick_name'] . '%');
                }
            })
            ->skip($offset)
            ->take($params['limit'])
            ->orderByDesc('order.created_at')
            ->get()
            ->toArray();
        return $data;
    }

    public static function orderListCount($params)
    {
        $data = Order::leftJoin('product', 'product.prod_id', '=', 'order.prod_id')
            ->leftJoin('user', 'user.user_id', '=', 'order.user_id')
            ->select('order.*', 'user.nick_name', 'user.user_id', 'user.avatarUrl', 'product.prod_id', 'product.prod_title', 'product.prod_img')
            ->where(function ($query) use ($params) {
                if (!is_null($params['order_sn'])) {
                    $query->where('order.order_sn', 'like', '%' . $params['order_sn'] . '%');
                }
            })
            ->where(function ($query) use ($params) {
                if (!is_null($params['status'])) {
                    $query->where('order.status', 'like', '%' . $params['status'] . '%');
                }
            })
            ->where(function ($query) use ($params) {
                if (!is_null($params['prod_title'])) {
                    $query->where('product.prod_title', 'like', '%' . $params['prod_title'] . '%');
                }
            })
            ->where(function ($query) use ($params) {
                if (!is_null($params['nick_name'])) {
                    $query->where('user.nick_name', 'like', '%' . $params['nick_name'] . '%');
                }
            })
            ->get()
            ->toArray();
        return count($data);
    }
}