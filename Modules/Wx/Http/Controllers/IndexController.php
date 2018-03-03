<?php
/**
 * 小程序
 * Author: CK
 * Date: 2018/2/25
 */

namespace Modules\Wx\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class IndexController extends Controller
{
    /**
     *首页数据
     * @return array
     */
    public function protalData(Request $request)
    {
        $params = $request->all();
        $result = \IndexService::protalData($params);
        return $result;
    }

    /**
     *发现页面数据
     * @return array
     */
    public function findData(Request $request)
    {
        $params = $request->all();
        $result = \IndexService::findData($params);
        return $result;
    }

    /**
     *发现商品详情
     * @return array
     */
    public function findDataDeatil(Request $request)
    {
        $params = $request->all();
        $result = \IndexService::findDataDeatil($params);
        return $result;
    }

    /**
     * 点赞的添加
     * @return array
     */
    public function likeAdd(Request $request)
    {
        $params = $request->all();
        $result = \IndexService::likeAdd($params);
        return $result;
    }

    /**
     * 点赞的删除
     * @return array
     */
    public function likeDelete(Request $request)
    {
        $params = $request->all();
        $result = \IndexService::likeDelete($params);
        return $result;
    }

    /**
     * 评论的添加
     * @return array
     */
    public function commentAdd(Request $request)
    {
        $params = $request->all();
        $result = \IndexService::commentAdd($params);
        return $result;
    }

    /**
     * 评论的删除
     * @return array
     */
    public function commentDelete(Request $request)
    {
        $params = $request->all();
        $result = \IndexService::commentAdd($params);
        return $result;
    }

    /**
     * 个人订单
     * @return array
     */
    public function orderList(Request $request)
    {
        $params = $request->all();
        $result = \IndexService::orderList($params);
        return $result;
    }

    /**
     * 订单的添加
     * @return array
     */
    public function orderAdd(Request $request)
    {
        $params = $request->all();
        $result = \IndexService::orderAdd($params);
        return $result;
    }

    /**
     * 订单的修改
     * @return array
     */
    public function orderEdit(Request $request)
    {
        $params = $request->all();
        $result = \IndexService::orderEdit($params);
        return $result;
    }

    /**
     * 订单的详情
     * @return array
     */
    public function orderDetail(Request $request)
    {
        $params = $request->all();
        $result = \IndexService::orderDetail($params);
        return $result;
    }

}
