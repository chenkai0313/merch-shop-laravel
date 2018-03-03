<?php
/**
 * 商家配置
 * Author: CK
 * Date: 2018/2/25
 */

namespace Modules\Backend\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class ConfigController extends Controller
{
    /**
     * 端配置修改
     * @return array
     */
    public function configEdit(Request $request)
    {
        $params = $request->all();
        $result = \ConfigService::configEdit($params);
        return $result;
    }

    /**
     * 配置详情
     * @return array
     */
    public function configDetail(Request $request)
    {
        $params = $request->all();
        $result = \ConfigService::configDetail($params);
        return $result;
    }


}
