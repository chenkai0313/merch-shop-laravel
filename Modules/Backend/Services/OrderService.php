<?php
/**
 * 订单
 * Author: CK
 * Date: 2018/2/25
 */

namespace Modules\Backend\Services;


use Modules\Backend\Models\Order;

class OrderService
{
    /**
     * 订单的添加
     * @return array
     */
    public function orderAdd($params)
    {
        if (!isset($params['type'])) {
            return ['code' => 90002, 'msg' => '类型必选'];
        }
        if ($params['type'] == 2) {
            if (!isset($params['prod_id'])) {
                return ['code' => 90002, 'msg' => '商品id必填'];
            }
        }
        $params['order_sn'] = 'PAY' . get_sn();
        $validator = \Validator::make($params, [
            'user_id' => 'required',
            'status' => 'required',
            'prod_price'=> 'required',
        ], [
            'required' => ':attribute必填',
        ], [
            'user_id' => '用户id',
            'status' => '支付状态',
            'prod_price' => '商品价格',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $data = Order::orderAdd($params);
        if ($data) {
            $result['code'] = 1;
            $result['msg'] = "添加成功";
        } else {
            $result['code'] = 90002;
            $result['msg'] = "添加失败";
        }
        return $result;
    }

    /**
     * 订单的修改
     * @return array
     */
    public function orderEdit($params)
    {
        if (!isset($params['type'])) {
            return ['code' => 90002, 'msg' => '类型必选'];
        }
        if ($params['type'] == 2) {
            if (!isset($params['prod_id'])) {
                return ['code' => 90002, 'msg' => '商品id必填'];
            }
        }
        $validator = \Validator::make($params, [
            'user_id' => 'required',
            'status' => 'required',
            'prod_price'=> 'required',
        ], [
            'required' => ':attribute必填',
        ], [
            'user_id' => '用户id',
            'status' => '支付状态',
            'prod_price' => '商品价格',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $data = Order::orderEdit($params);
        if ($data) {
            $result['code'] = 1;
            $result['msg'] = "修改成功";
        } else {
            $result['code'] = 90002;
            $result['msg'] = "修改失败";
        }
        return $result;
    }

    /**
     * 订单的详情
     * @return array
     */
    public function orderDetail($params)
    {
        if (!isset($params['order_sn'])) {
            return ['code' => 90002, 'msg' => '订单号必填'];
        }
        $data = Order::orderDetail($params);
        if ($data) {
            return ['code' => 1, 'data' => $data];
        }
        return ['code' => 90002, 'msg' => '查询失败'];
    }

    /**
     * 订单的删除
     * @return array
     */
    public function orderDelete($params)
    {
        if (!isset($params['order_sn'])) {
            return ['code' => 90002, 'msg' => '订单号必填'];
        }
        $res = Order::orderDelete($params);
        if ($res) {
            return ['code' => 1, 'msg' => '删除成功'];
        }
        return ['code' => 90002, 'msg' => '删除失败'];
    }

    /**
     * 订单的列表
     * @return array
     */
    public function orderList($params)
    {
        $params['limit'] = isset($params['limit']) ? $params['limit'] : 20;
        $params['page'] = isset($params['page']) ? $params['page'] : 1;
        $params['order_sn'] = isset($params['order_sn']) ? $params['order_sn'] : null;
        $params['prod_title'] = isset($params['prod_title']) ? $params['prod_title'] : null;
        $params['nick_name'] = isset($params['nick_name']) ? $params['nick_name'] : null;
        $params['status'] = isset($params['status']) ? $params['status'] : null;
        $data['list'] = Order::orderList($params);
        $data['count'] = Order::orderListCount($params);
        $data['page'] = $params['page'];
        $data['limit'] = $params['limit'];
        return ['code' => 1, 'data' => $data];
    }
}
