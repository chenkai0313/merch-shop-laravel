<?php
/**
 * 商品
 * Author: CK
 * Date: 2018/2/25
 */

namespace Modules\Backend\Services;


use Modules\Backend\Models\Comment;
use Modules\Backend\Models\Like;
use Modules\Backend\Models\Product;

class ProductService
{
    /**
     * 商品的添加
     * @return array
     */
    public function productAdd($params)
    {
        if (!isset($params['prod_type'])) {
            return ['code' => 90002, 'msg' => '商品类型必填'];
        }
        if ($params['prod_type'] == 2) {
            if (!isset($params['prod_price'])) {
                return ['code' => 90002, 'msg' => '商品价格必填'];
            }
        }
        $validator = \Validator::make($params, [
            'prod_title' => 'required',
        ], [
            'required' => ':attribute必填',
        ], [
            'prod_title' => '商品标题',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $data = Product::productAdd($params);
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
     * 商品的修改
     * @return array
     */
    public function productEdit($params)
    {
        if (!isset($params['prod_id'])) {
            return ['code' => 90002, 'msg' => '商品id必填'];
        }
        if (!isset($params['prod_type'])) {
            return ['code' => 90002, 'msg' => '商品类型必填'];
        }
        if ($params['prod_type'] == 2) {
            if (!isset($params['prod_price'])) {
                return ['code' => 90002, 'msg' => '商品价格必填'];
            }
        }
        if ($params['prod_type'] == 1) {
            $params['prod_price'] = null;
        }
        $validator = \Validator::make($params, [
            'prod_title' => 'required',
        ], [
            'required' => ':attribute必填',
        ], [
            'prod_title' => '商品标题',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $data = Product::productEdit($params);
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
     * 商品的删除
     * @return array
     */
    public function productDelete($parmas)
    {
        if (!isset($params['prod_id'])) {
            return ['code' => 90002, 'msg' => '商品id必填'];
        }
        $res = Product::productDelete($parmas);
        if ($res) {
            return ['code' => 1, 'msg' => '删除成功'];
        }
        return ['code' => 90002, 'msg' => '删除失败'];
    }

    /**
     * 商品的详情
     * @return array
     */
    public function productDetail($params)
    {
        if (!isset($params['prod_id'])) {
            return ['code' => 90002, 'msg' => '商品id必填'];
        }
        $data = Product::productDetail($params);
        $data['prod_img'] = array_filter(explode('|', $data['prod_img']));
        if ($data) {
            return ['code' => 1, 'data' => $data];
        }
        return ['code' => 90002, 'msg' => '查询失败'];
    }

    /**
     * 商品的列表
     * @return array
     */
    public function productList($params)
    {
        $params['limit'] = isset($params['limit']) ? $params['limit'] : 20;
        $params['page'] = isset($params['page']) ? $params['page'] : 1;
        $params['prod_title'] = isset($params['prod_title']) ? $params['prod_title'] : '';
        $params['is_show'] = isset($params['is_show']) ? $params['is_show'] : null;
        $params['is_recommend'] = isset($params['is_recommend']) ? $params['is_recommend'] : null;
        $data['list'] = Product::productList($params);
        foreach ($data['list'] as &$v) {
            $v['prod_img']=array_filter(explode('|',$v['prod_img']));
            $v['comment_count']=Comment::commentListCount($v);
            $v['like_count']=count(Like::likeList($v));
        }
        $data['count'] = Product::productListCount($params);
        $data['page'] = $params['page'];
        $data['limit'] = $params['limit'];
        return ['code' => 1, 'data' => $data];
    }
}
