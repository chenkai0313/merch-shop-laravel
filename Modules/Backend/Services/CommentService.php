<?php
/**
 * 评论
 * Author: CK
 * Date: 2018/2/25
 */

namespace Modules\Backend\Services;

use Modules\Backend\Models\Comment;

class CommentService
{
    /**
     * 评论的添加
     * @return array
     */
    public function commentAdd($params)
    {
        $validator = \Validator::make($params, [
            'user_id' => 'required',
            'prod_id' => 'required',
            'cmt_content' => 'required|max:255',
        ], [
            'required' => ':attribute必填',
            'max' => ':attribute最大为255字节'
        ], [
            'cmt_content' => '评论内容',
            'user_id' => '用户id',
            'prod_id' => '商品id',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $data = Comment::commentAdd($params);
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
     * 评论的修改
     * @return array
     */
    public function commentEdit($params)
    {
        $validator = \Validator::make($params, [
            'cmt_id' => 'required',
            'cmt_content' => 'required|max:255',
            'user_id' => 'required',
            'prod_id' => 'required',
        ], [
            'required' => ':attribute必填',
            'max' => ':attribute最大为255字节'
        ], [
            'cmt_id' => '评论id',
            'cmt_content' => '评论内容',
            'user_id' => '用户id',
            'prod_id' => '商品id',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $res = Comment::commentEdit($params);
        if ($res) {
            $result['code'] = 1;
            $result['msg'] = "修改成功";
        } else {
            $result['code'] = 90002;
            $result['msg'] = "修改失败";
        }
        return $result;
    }

    /**
     * 管理员评论的删除
     * @return array
     */
    public function commentDelete($params)
    {
        if (!isset($params['cmt_id'])) {
            return ['code' => 90002, '商品id必填'];
        }
        $data = Comment::commentDelete($params);
        if ($data) {
            $result['code'] = 1;
            $result['msg'] = "删除成功";
        } else {
            $result['code'] = 90002;
            $result['msg'] = "删除失败";
        }
        return $result;
    }

    /**
     * 评论的详情
     * @return array
     */
    public function commentDetail($params)
    {
        if (!isset($params['cmt_id'])) {
            return ['code' => 90002, '商品id必填'];
        }
        $data = Comment::commentDetail($params);
        if ($data) {
            $result['code'] = 1;
            $result['data'] = $data;
        } else {
            $result['code'] = 90002;
            $result['msg'] = "查询失败";
        }
        return $result;
    }

    /**
     * 评论的列表
     * @return array
     */
    public function commentList($params)
    {
        if (!isset($params['prod_id'])) {
            return ['code' => 90002, '商品id必填'];
        }
        $params['limit'] = isset($params['limit']) ? $params['limit'] : 20;
        $params['page'] = isset($params['page']) ? $params['page'] : 1;
        $params['prod_title'] = isset($params['prod_title']) ? $params['prod_title'] : '';
        $params['nick_name'] = isset($params['nick_name']) ? $params['nick_name'] : '';
        $data['list'] = Comment::commentList($params);
        $data['count'] = Comment::commentListCount($params);
        $data['page'] = $params['page'];
        $data['limit'] = $params['limit'];
        return ['code' => 1, 'data' => $data];
    }

}
