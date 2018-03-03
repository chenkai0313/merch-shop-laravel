<?php
/**
 * 点赞
 * Author: CK
 * Date: 2018/2/25
 */

namespace Modules\Backend\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class LikeController extends Controller
{


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
