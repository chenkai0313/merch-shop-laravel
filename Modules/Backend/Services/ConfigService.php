<?php
/**
 * 商家配置
 * Author: CK
 * Date: 2018/2/25
 */

namespace Modules\Backend\Services;

use Modules\Backend\Models\Config;

class ConfigService
{
    /**
     * 端配置修改
     * @return array
     */
    public function configEdit($params)
    {
        if (!isset($params['config_type'])) {
            return ['code' => 90002, 'msg' => '未选择修改类型'];
        }
        $data['config_content'] = serialize($params['config_content']);
        $res = Config::configEdit($params, $data);
        if ($res) {
            $result['code'] = 1;
            $result['msg'] = "添加成功";
        } else {
            $result['code'] = 90002;
            $result['msg'] = "添加失败";
        }
        return $result;
    }

    /**
     * 配置详情
     * @return array
     */
    public function configDetail($params)
    {
        if (!isset($params['config_type'])) {
            return ['code' => 90002, 'msg' => '未选择修改类型'];
        }
        $data = Config::configDetail($params);
        if ($params['config_type'] == 2) {
            $res['config_type'] = $data['config_type'];
            $res['config_content'] = $data['config_content'];
            $res['config_content']['shop_img_list'] = explode('|', $res['config_content']['shop_img']);
            if ($res) {
                return ['code' => 1, 'data' => $res];
            }
        }
        if ($data) {
            return ['code' => 1, 'data' => $data];
        }
        return ['code' => 90002, 'msg' => '查询失败'];
    }
}
