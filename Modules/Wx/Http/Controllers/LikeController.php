<?php
/**
 * 点赞
 * Author: CK
 * Date: 2018/2/25
 */

namespace Modules\Wx\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class LikeController extends Controller
{
    /**
     * 点赞的添加
     * @return array
     */
    public function likeAdd(Request $request)
    {
        $params = $request->all();
        $result = \LikeService::configEdit($params);
        return $result;
    }

    /**
     * 点赞的删除
     * @return array
     */
    public function configDetail(Request $request)
    {
        $params = $request->all();
        $result = \LikeService::likeDelete($params);
        return $result;
    }

    /**
     * 点赞的列表
     * @return array
     */
    public function likeList(Request $request)
    {
        $params = $request->all();
        $result = \LikeService::likeList($params);
        return $result;
    }
}
