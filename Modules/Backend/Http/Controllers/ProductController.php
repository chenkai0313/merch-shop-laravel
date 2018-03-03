<?php
/**
 * 商品
 * Author: CK
 * Date: 2018/2/25
 */

namespace Modules\Backend\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class ProductController extends Controller
{
    /**
     * 商品的添加
     * @return array
     */
    public function productAdd(Request $request)
    {
        $params = $request->all();
        $result = \ProductService::productAdd($params);
        return $result;
    }

    /**
     * 商品的修改
     * @return array
     */
    public function productEdit(Request $request)
    {
        $params = $request->all();
        $result = \ProductService::productEdit($params);
        return $result;
    }

    /**
     * 商品的详情
     * @return array
     */
    public function productDetail(Request $request)
    {
        $params = $request->all();
        $result = \ProductService::productDetail($params);
        return $result;
    }

    /**
     * 商品的删除
     * @return array
     */
    public function productDelete(Request $request)
    {
        $params = $request->all();
        $result = \ProductService::productDelete($params);
        return $result;
    }

    /**
     * 商品的列表
     * @return array
     */
    public function productList(Request $request)
    {
        $params = $request->all();
        $result = \ProductService::productList($params);
        return $result;
    }
}
