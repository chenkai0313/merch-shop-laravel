<?php
/**
 * 轮播图
 * Author: CK
 * Date: 2018/2/25
 */

namespace Modules\Backend\Services;

use Modules\Backend\Models\Adv;

class AdvService
{
    /**
     * 轮播图的添加
     * @return array
     */
    public function advAdd($params)
    {
        $validator = \Validator::make($params, [
            'adv_img' => 'required',
        ], [
            'required' => ':attribute必填',
        ], [
            'adv_img' => '图片路径',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $data = Adv::advAdd($params);
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
     * 轮播图的修改
     * @return array
     */
    public function advEdit($params)
    {
        $validator = \Validator::make($params, [
            'adv_id' => 'required',
            'adv_img' => 'required',
        ], [
            'required' => ':attribute必填',
        ], [
            'adv_id' => 'id',
            'adv_img' => '图片路径',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $data = Adv::advEdit($params);
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
     * 轮播图的删除
     * @return array
     */
    public function advDelete($parmas)
    {
        if (!isset($parmas['adv_id'])) {
            return ['code' => 90002, 'msg' => 'id必填'];
        }
        $res = Adv::advDelete($parmas);
        if ($res) {
            return ['code' => 1, 'msg' => '删除成功'];
        }
        return ['code' => 90002, 'msg' => '删除失败'];
    }

    /**
     * 轮播图的详情
     * @return array
     */
    public function advDetail($params)
    {
        if (!isset($params['adv_id'])) {
            return ['code' => 1, 'msg' => 'id必填'];
        }
        $data = Adv::advDetail($params);
        if ($data) {
            return ['code' => 1, 'data' => $data];
        }
        return ['code' => 90002, 'msg' => '查询失败'];
    }

    /**
     * 轮播图的列表
     * @return array
     */
    public function advList($params)
    {
        $params['limit'] = isset($params['limit']) ? $params['limit'] : 20;
        $params['page'] = isset($params['page']) ? $params['page'] : 1;
        $params['adv_title'] = isset($params['adv_title']) ? $params['adv_title'] : '';
        $params['is_show'] = isset($params['is_show']) ? $params['is_show'] : null;
        $data['list'] = Adv::advList($params);
        $data['count'] = Adv::advListCount($params);
        $data['page'] = $params['page'];
        $data['limit'] = $params['limit'];
        return ['code' => 1, 'data' => $data];
    }
}
