<?php
/**
 * 订单
 * Author: CK
 * Date: 2018/2/25
 */

namespace Modules\Backend\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class OrderController extends Controller
{
    /**
     * 订单的添加
     * @return array
     */
    public function orderAdd(Request $request)
    {
        $params = $request->all();
        $result = \OrderService::orderAdd($params);
        return $result;
    }

    /**
     * 订单的修改
     * @return array
     */
    public function orderEdit(Request $request)
    {
        $params = $request->all();
        $result = \OrderService::orderEdit($params);
        return $result;
    }

    /**
     * 订单的详情
     * @return array
     */
    public function orderDetail(Request $request)
    {
        $params = $request->all();
        $result = \OrderService::orderDetail($params);
        return $result;
    }

    /**
     * 订单的删除
     * @return array
     */
    public function orderDelete(Request $request)
    {
        $params = $request->all();
        $result = \OrderService::orderDelete($params);
        return $result;
    }

    /**
     * 订单的列表
     * @return array
     */
    public function orderList(Request $request)
    {
        $params = $request->all();
        $result = \OrderService::orderList($params);
        return $result;
    }
}
