<?php
/**
 * 轮播图
 * Author: CK
 * Date: 2018/2/25
 */

namespace Modules\Backend\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class AdvController extends Controller
{
    /**
     * 轮播图的添加
     * @return array
     */
    public function advAdd(Request $request)
    {
        $params = $request->all();
        $result = \AdvService::advAdd($params);
        return $result;
    }

    /**
     * 轮播图的修改
     * @return array
     */
    public function advEdit(Request $request)
    {
        $params = $request->all();
        $result = \AdvService::advEdit($params);
        return $result;
    }

    /**
     * 轮播图的删除
     * @return array
     */
    public function advDelete(Request $request)
    {
        $params = $request->all();
        $result = \AdvService::advDelete($params);
        return $result;
    }

    /**
     * 轮播图的详情
     * @return array
     */
    public function advDetail(Request $request)
    {
        $params = $request->all();
        $result = \AdvService::advDetail($params);
        return $result;
    }

    /**
     * 轮播图的列表
     * @return array
     */
    public function advList(Request $request)
    {
        $params = $request->all();
        $result = \AdvService::advList($params);
        return $result;
    }
}
