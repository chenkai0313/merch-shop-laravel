<?php
/**
 * 小程序
 * Author: ck
 * Date: 2018/1/20
 */

namespace Modules\Wx\Services;

use Modules\Backend\Models\Adv;
use Modules\Backend\Models\Comment;
use Modules\Backend\Models\Like;
use Modules\Backend\Models\Order;
use Modules\Backend\Models\Product;
use Modules\Backend\Services\CommentService;
use Modules\Backend\Services\ConfigService;
use Modules\Backend\Services\LikeService;
use Modules\Backend\Services\OrderService;

class IndexService
{
    /**
     * 首页数据
     * @return array
     */
    public function protalData($params)
    {
        $data['adv_list'] = Adv::where('is_show', 1)
            ->select('adv_img', 'adv_title')
            ->orderByDesc('sort')
            ->orderByDesc('created_at')
            ->get();
        $params['config_type'] = 2;
        $config = new ConfigService();
        $config_content = $config->configDetail($params);
        $data['shop_detail'] = $config_content['data']['config_content'];
        $unset_data = ['appid', 'pay_mchid', 'pay_apikey', 'secret'];
        for ($i = 0; $i < count($unset_data); $i++) {
            unset($data['shop_detail'][$unset_data[$i]]);
        }
        $data['product_list'] = Product::select('prod_title', 'prod_img', 'prod_id')
            ->where('is_show', 1)
            ->orderByDesc('is_recommend')
            ->orderByDesc('prod_sort')
            ->orderByDesc('updated_at')
            ->limit(2)
            ->get();
        foreach ($data['product_list'] as $v) {
            $v['prod_img'] = array_filter(explode('|', $v['prod_img']))[0];
            $v['like_num'] = Like::where('prod_id', $v['prod_id'])->count();
            $v['comment_num'] = Comment::where('prod_id', $v['prod_id'])->count();
        }
        return ['code' => 1, 'data' => $data];
    }

    /**
     * 发现页面数据
     * @return array
     */
    public function findData($params)
    {
        $params['limit'] = isset($params['limit']) ? $params['limit'] : 10;
        $params['page'] = isset($params['page']) ? $params['page'] : 1;
        $offset = ($params['page'] - 1) * $params['limit'];
        $params['config_type'] = 2;
        $config = new ConfigService();
        $config_content = $config->configDetail($params);
        $data['shop_detail']['bg_img'] = $config_content['data']['config_content']['bg_img'];
        $data['shop_detail']['wx_qrcode'] = $config_content['data']['config_content']['wx_qrcode'];
        $data['product_list'] = Product::select('prod_id', 'prod_type', 'prod_price', 'prod_title', 'prod_img', 'updated_at')
            ->where('is_show', 1)
            ->orderByDesc('is_recommend')
            ->orderByDesc('prod_sort')
            ->orderByDesc('updated_at')
            ->skip($offset)
            ->take($params['limit'])
            ->get();
        $data['page'] = $params['page'];
        $data['limit'] = $params['limit'];
        $data['count'] = count($data['product_list']);
        for ($i = 0; $i < count($data['product_list']); $i++) {
            $data['product_list'][$i] ['before_present'] = intval((time() - strtotime($data['product_list'][$i]['updated_at'])) / (3600 * 24)) . '天前';
            $data['product_list'][$i] ['app_name'] = $config_content['data']['config_content']['app_name'];
            $data['product_list'][$i] ['app_avatarUrl'] = $config_content['data']['config_content']['app_avatarUrl'];
            $data['product_list'][$i]['prod_img'] = array_filter(explode('|', $data['product_list'][$i]['prod_img']));
            $data['product_list'][$i]['like_num'] = Like::where('prod_id', $data['product_list'][$i]['prod_id'])->count();
            $data['product_list'][$i]['like_num_list'] = Like::leftJoin('user', 'user.user_id', '=', 'like.user_id')
                ->select('user.avatarUrl', 'user.user_id')
                ->where('like.prod_id', $data['product_list'][$i]['prod_id'])
                ->orderByDesc('like.created_at')
                ->limit(10)
                ->get();
            $data['product_list'][$i]['comment_num'] = Comment::where('prod_id', $data['product_list'][$i]['prod_id'])->count();
            $data['product_list'][$i]['comment_num_list'] = Comment::leftJoin('user', 'user.user_id', '=', 'comment.user_id')
                ->select('user.nick_name', 'comment.cmt_content', 'user.user_id')
                ->where('comment.prod_id', $data['product_list'][$i]['prod_id'])
                ->orderByDesc('comment.created_at')
                ->limit(5)
                ->get();
        }
        return ['code' => 1, 'data' => $data];
    }

    /**
     * 发现商品详情
     * @return array
     */
    public function findDataDeatil($params)
    {
        if (!isset($params['prod_id'])) {
            return ['code' => 90002, 'msg' => '商品id必填'];
        }
        try {
            $params['config_type'] = 2;
            $config = new ConfigService();
            $config_content = $config->configDetail($params);
            $data = Product::select('prod_id', 'prod_type', 'prod_price', 'prod_title', 'prod_img', 'updated_at')
                ->where('is_show', 1)
                ->where('prod_id', $params['prod_id'])
                ->first();
            $data['prod_img'] = array_filter(explode('|', $data['prod_img']));
            $data['app_name'] = $config_content['data']['config_content']['app_name'];
            $data['app_avatarUrl'] = $config_content['data']['config_content']['app_avatarUrl'];
            $data['before_present'] = intval((time() - strtotime($data['updated_at'])) / (3600 * 24)) . '天前';
            $data['like_num_list'] = Like::leftJoin('user', 'user.user_id', '=', 'like.user_id')
                ->select('user.avatarUrl', 'user.user_id')
                ->where('like.prod_id', $data['prod_id'])
                ->orderByDesc('like.created_at')
                ->get();
            $data['product_list'] = Comment::leftJoin('user', 'user.user_id', '=', 'comment.user_id')
                ->select('user.nick_name', 'comment.cmt_content', 'user.user_id')
                ->where('comment.prod_id', $data['prod_id'])
                ->orderByDesc('comment.created_at')
                ->get();
            return ['code' => 1, 'data' => $data];
        } catch (Exception $e) {
            return ['code' => 90002, 'msg' => '未查询到数据'];
        }
    }

    /**
     * 点赞的添加
     * @return array
     */
    public function likeAdd($params)
    {
        $like = new LikeService();
        return $like->likeAdd($params);
    }

    /**
     * 点赞的删除
     * @return array
     */
    public function likeDelete($params)
    {
        $like = new LikeService();
        return $like->likeDelete($params);
    }

    /**
     * 评论的添加
     * @return array
     */
    public function commentAdd($params)
    {
        $like = new CommentService();
        return $like->commentAdd($params);
    }

    /**
     * 点赞的删除
     * @return array
     */
    public function commentDelete($params)
    {
        $like = new CommentService();
        return $like->commentDelete($params);
    }

    /**
     * 个人订单
     * @return array
     */
    public function orderList($params)
    {
        if (!isset($params['user_id'])) {
            return ['code' => 90002, 'msg' => '用户id必填'];
        }
        if (!isset($params['type'])) {
            return ['code' => 90002, 'msg' => '订单类型必填'];
        }
        $params['limit'] = isset($params['limit']) ? $params['limit'] : 10;
        $params['page'] = isset($params['page']) ? $params['page'] : 1;
        $offset = ($params['page'] - 1) * $params['limit'];
        if ($params['type'] == 1) {
            $data['list'] = Order::where('type', $params['type'])
                ->orderByDesc('updated_at')
                ->skip($offset)
                ->take($params['limit'])
                ->get();
            $data['page'] = $params['page'];
            $data['limit'] = $params['limit'];
            $data['count'] = count($data['list']);
        } elseif ($params['type'] == 2) {
            $data['list'] = Order::leftJoin('product', 'product.prod_id', '=', 'order.prod_id')
                ->select('order.*', 'product.prod_id', 'product.prod_title', 'product.prod_img', 'product.prod_img')
                ->where('order.type', $params['type'])
                ->orderByDesc('order.updated_at')
                ->skip($offset)
                ->take($params['limit'])
                ->get();
            for ($i = 0; $i < count($data['list']); $i++) {
                $data['list'][$i]['prod_img'] = array_filter(explode('|', $data['list'][$i]['prod_img']));
            }
            $data['page'] = $params['page'];
            $data['limit'] = $params['limit'];
            $data['count'] = count($data['list']);
        }
        return ['code' => 1, 'data' => $data];
    }

    /**
     * 订单的添加
     * @return array
     */
    public function orderAdd($params)
    {
        $like = new OrderService();
        return $like->orderAdd($params);
    }

    /**
     * 订单的修改
     * @return array
     */
    public function orderEdit($params)
    {
        $like = new OrderService();
        return $like->orderEdit($params);
    }

    /**
     * 订单的详情
     * @return array
     */
    public function orderDetail($params)
    {
        $like = new OrderService();
        return $like->orderDetail($params);
    }
}
