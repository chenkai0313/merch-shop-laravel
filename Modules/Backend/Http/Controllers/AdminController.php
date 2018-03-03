<?php
/**
 * 管理员模块
 * Author: ck
 * Date: 2018/2/25
 */

namespace Modules\Backend\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Gregwar\Captcha\CaptchaBuilder;
use Session;
use Cache;

class AdminController extends Controller
{
    /**
     * 管理员添加
     */
    public function adminAdd(Request $request)
    {
        $params = $request->all();
        $result = \AdminService::adminAdd($params);
        return $result;
    }

    /**
     * 管理员编辑
     */
    public function adminEdit(Request $request)
    {
        $params = $request->all();
        $result = \AdminService::adminEdit($params);
        return $result;
    }

    /**                                                                                                 Í
     * 管理员详细
     */
    public function adminDetail(Request $request)
    {
        $params = $request->all();
        $result = \AdminService::adminDetail($params['admin_id']);
        return $result;
    }

    /**
     * 管理员登录
     */
    public function adminLogin(Request $request)
    {
        $params = $request->all();
//        \Log::info('adminLogin');
//        \Log::info($request->session()->all());
        #取出session,并删除
        if (!isset($params['time'])) {
            return ['code' => 90002, 'msg' => 'time必填'];
        }
        $params['captcha'] = $request->session()->pull('captcha' . $params['time'], null);
        $result = \AdminService::adminLogin($params);
        return $result;
    }

    /**
     * 用户修改password
     */
    public function adminChangePassword(Request $request)
    {
        $params = $request->input();
        $params['admin_id'] = get_admin_id();
        $result = \AdminService::adminChangePassword($params);
        return $result;
    }

    /**
     * 验证码生成
     * @params  [type] $tmp [description]
     */
    public function qrcode($tmp)
    {
        //生成验证码图片的Builder对象，配置相应属性
        $data = getRandom(4);
        $builder = new CaptchaBuilder($data);
        //可以设置图片宽高及字体
        $builder->build($width = 150, $height = 50, $font = null);
        //获取验证码的内容
        $phrase = $builder->getPhrase();
        //把内容存入session
        $key = $tmp;
        $key = 'captcha' . $key;
        session([$key => $phrase]);
        ob_clean();
        return response($builder->output())->header('Content-type', 'image/jpeg'); //把验证码数据以jpeg图片的格式输出
    }


}
