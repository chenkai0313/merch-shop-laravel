<?php
/**
 * 评论
 * Author: CK
 * Date: 2018/2/25
 */

namespace Modules\Backend\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class CommentController extends Controller
{
    /**
     * 评论的添加
     * @return array
     */
    public function commentAdd(Request $request)
    {
        $params = $request->all();
        $result = \CommentService::commentAdd($params);
        return $result;
    }

    /**
     * 评论的删除
     * @return array
     */
    public function commentDelete(Request $request)
    {
        $params = $request->all();
        $result = \CommentService::commentDelete($params);
        return $result;
    }

    /**
     * 评论的修改
     * @return array
     */
    public function commentEdit(Request $request)
    {
        $params = $request->all();
        $result = \CommentService::commentEdit($params);
        return $result;
    }

    /**
     * 评论的详情
     * @return array
     */
    public function commentDetail(Request $request)
    {
        $params = $request->all();
        $result = \CommentService::commentDetail($params);
        return $result;
    }

    /**
     * 评论的列表
     * @return array
     */
    public function commentList(Request $request)
    {
        $params = $request->all();
        $result = \CommentService::commentList($params);
        return $result;
    }

    /**
     * 所有评论列表
     * @return array
     */
    public function commentListAll(Request $request)
    {
        $params = $request->all();
        $result = \CommentService::commentListAll($params);
        return $result;
    }
}
