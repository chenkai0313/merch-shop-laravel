<?php
/**
 * 点赞
 * Author: CK
 * Date: 2018/2/25
 */

namespace Modules\Backend\Services;

use Modules\Backend\Models\Like;

class LikeService
{
    /**
     * 点赞的添加
     * @return array
     */
    public function likeAdd($params)
    {
        $validator = \Validator::make($params, [
            'prod_id' => 'required',
            'user_id' => 'required',
        ], [
            'required' => ':attribute必填',
        ], [
            'prod_id' => '商品id',
            'user_id' => '用户id',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $exist=Like::likeExist($params);
        if($exist){
            return ['code'=>90002,'msg'=>'您已经点赞过啦'];
        }
        $data = Like::likeAdd($params);
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
     * 点赞的删除
     * @return array
     */
    public function likeDelete($params)
    {
        $validator = \Validator::make($params, [
            'prod_id' => 'required',
            'user_id' => 'required',
        ], [
            'required' => ':attribute必填',
        ], [
            'prod_id' => '商品id',
            'user_id' => '用户id',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $res = Like::likeDelete($params);
        if ($res) {
            $result['code'] = 1;
            $result['msg'] = "取赞成功";
        } else {
            $result['code'] = 90002;
            $result['msg'] = "取赞失败";
        }
        return $result;
    }

    /**
     * 点赞的列表
     * @return array
     */
    public function likeList($params)
    {
        if (!isset($params['prod_id'])) {
            return ['code' => 90002, 'msg'=>'商品id必填'];
        }
        $data = Like::likeList($params);
        $res['list'] = $data;
        $res['count'] = count($data);
        return ['code' => 1, 'data' => $res];
    }
}
