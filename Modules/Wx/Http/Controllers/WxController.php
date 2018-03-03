<?php


namespace Modules\Wx\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WxController extends Controller
{
    public function __construct()
    {
        $config = array(
            'appid' => get_wx_app()['appid'],//小程序appid
            'pay_mchid' => get_wx_app()['pay_mchid'],//商户号
            'pay_apikey' => get_wx_app()['pay_apikey'],//可在微信商户后台生成支付秘钥
            'secret' => get_wx_app()['secret'],
        );
        $this->config = $config;
    }

    /**
     * 获取用户的openid
     * @return array
     */
    public function getOpenid($params)
    {
        if (!isset($params['code'])) {
            return ['code' => 90002, 'msg' => 'code不能为空'];
        }
        $config = $this->config;
        $grant_type = 'authorization_code';
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=';
        $code = $params['code'];
        $Url = '' . $url . '' . $config['appid'] . '&secret=' . $config['secret'] . '&js_code=' . $code . '&grant_type=' . $grant_type . '';
        return json_decode(vget($Url), true);
    }

    /**
     * 获取小程序二维码(base64输出)
     * @return array
     */
    public function getQrcodeConfig(Request $request)
    {
        $params = $request->all();
        $config = $this->config;
        if (!isset($params['path'])) {
            return ['code' => 90002, 'msg' => 'path不能为空'];
        }
        $urlaccess_token = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $config['appid'] . '&secret=' . $config['secret'] . '';
        $access_token = json_decode(vget($urlaccess_token), true);
        $token = $access_token['access_token'];
        $url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $token . '';
        $path_data = '{"scene":"' . $this->getRangChar() . '","path": "' . $params['path'] . '", "width": 430,"auto_color":false,"line_color":{"r":"0","g":"0","b":"0"}}';
        $res = $this->curl_post_ssl($url, $path_data);
        $data = 'data:image/jpg;base64,' . base64_encode($res) . ''; //以jpg图片格式保存
        $file_url = $this->base64_image_content($data, '' . storage_path() . '/app/wx/');
        return $file_url;
    }

    /**
     * 企业付款（提现配置）
     * @return array
     */
    public function getRefundConfig(Request $request)
    {
        $params = $request->all();
        if (!isset($params['openid'])) {
            return ['code' => 90002, 'msg' => 'openid不存在'];
        }
        #提现订单号
        if (!isset($params['order_sn'])) {
            return ['code' => 90002, 'msg' => 'openid不存在'];
        }
        #提现金额
        if (!isset($params['money'])) {
            return ['code' => 90002, 'msg' => 'openid不存在'];
        }
        $config = $this->config;
        $data = [
            'mch_appid' => $config['appid'],
            'mchid' => $config['pay_mchid'],
            'nonce_str' => $this->getRangChar(),
            'partner_trade_no' => $params['order_sn'],
            'openid' => $params['openid'],
            'check_name' => 'NO_CHECK',
            're_user_name' => 'DaTi',
            'amount' => $params['money'] * 100,
            'desc' => '提现费用',
            'spbill_create_ip' => $request->getClientIp(),
        ];
        #签名
        $data['sign'] = self::makeSign($data);
        #数组转xml
        $xmldata = self::array2xml($data);
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $res = self::curl($url, $xmldata);
        if (!$res) {
            return ['code' => 90002, 'msg' => '系统错误,请稍后再试'];
        }
        #xml转数组
        $content = self::xml2array($res);
        if ($content['result_code'] == 'FAIL') {
            return self::refunErr($content['err_code']);
        }
        return ['code' => 1, 'msg' => '提现成功'];
    }

    /**
     * 小程序用户支付
     * @return array
     */
    public function getPayConfig(Request $request)
    {
        $params = $request->all();
        if (!isset($params['order_sn'])) {
            return ['code' => 90002, 'msg' => '订单号不存在'];
        }
        if (!isset($params['openid'])) {
            return ['code' => 90002, 'msg' => 'openid不存在'];
        }
        if (!isset($params['money'])) {
            return ['code' => 90002, 'msg' => '金额不存在'];
        }
        $config = $this->config;
        $data = [
            'appid' => $config['appid'],
            'mch_id' => $config['pay_mchid'],
            'nonce_str' => $this->getRangChar(),
            'body' => '用户支付',
            'out_trade_no' => $params['order_sn'],
            'total_fee' => $params['money'] * 100,
            'spbill_create_ip' => $request->getClientIp(),
            'notify_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/backend/pay-notify',//call-back地址
            'trade_type' => 'JSAPI',
            'openid' => $params['openid'],
        ];
        #签名
        $data['sign'] = self::makeSign($data);
        #数组转xml
        $xmldata = self::array2xml($data);
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $res = self::curl_post_ssl($url, $xmldata);
        if (!$res) {
            return ['code' => 90002, 'msg' => '支付失败'];
        }
        #xml转数组
        $content = self::xml2array($res);
        $result = $this->pay($content);
        return ['code' => 1, 'data' => $result];
    }

    /**
     * 敏感字过滤
     * @return array
     */
    public function filterWord(Request $request)
    {
        $params = $request->all();
        if (!isset($params['word'])) {
            return ['code' => 90002, 'msg' => '需过滤文字不能为空'];
        }
        $data = [
            'str' => $params['word'],
            'lv' => 1
        ];
        $url = 'http://www.hoapi.com/index.php/Home/Api/check';
        $res = json_decode($this->curl($url, $data), true);
        if ($res['status']) {
            return ['code' => 1, 'msg' => '检测通过'];
        }
        return ['code' => 90002, 'msg' => '含有敏感字'];
    }

    /**
     *call-back地址
     * @return array
     */
    public function notify()
    {
        return 1;
    }

    /**
     *支付加密
     * @return array
     */
    public function pay($content)
    {
        $data = array(
            'appId' => $content['appid'],
            'timeStamp' => time(),
            'nonceStr' => $this->getRangChar(),
            'package' => 'prepay_id=' . $content['prepay_id'],
            'signType' => 'MD5'
        );
        $data['paySign'] = self::makeSign($data);
        return $data;
    }

    /**
     *过滤提现错误代码
     * @return array
     */
    public function refunErr($params)
    {
        $err = ['NO_AUTH', 'AMOUNT_LIMIT', 'PARAM_ERROR', 'OPENID_ERROR', 'SEND_FAILED', 'NOTENOUGH', 'SYSTEMERROR', 'NAME_MISMATCH'
            , 'SIGN_ERROR', 'XML_ERROR', 'FATAL_ERROR', 'FREQ_LIMIT', 'MONEY_LIMIT', 'CA_ERROR', 'V2_ACCOUNT_SIMPLE_BAN', 'PARAM_IS_NOT_UTF8', 'AMOUNT_LIMIT'];
        if (in_array($params, $err)) {
            return ['code' => 90002, 'msg' => '请求超时,请稍后再试'];
        }
    }

    /**
     * curl（post 带密钥）
     * @return array
     */
    public function curl($url, $xmldata, $second = 30, $aHeader = array())
    {
        $ch = curl_init();
        //超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if (count($aHeader) >= 1) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmldata);

        curl_setopt($ch, CURLOPT_SSLCERT, getcwd() . '/cert/apiclient_cert.pem');
        curl_setopt($ch, CURLOPT_SSLKEY, getcwd() . '/cert/apiclient_key.pem');


        $data = curl_exec($ch);
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            echo "call faild, errorCode:$error\n";
            curl_close($ch);
            return false;
        }
    }

    /**
     * 将一个XML转换为array结构的字符串
     * @return array
     */
    protected function xml2array($xml)
    {
        //禁止引用外部xml实体
        (true);
        $result = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $result;
    }

    /**
     * 将一个array转换为XML结构的字符串
     * @return array
     */
    protected function array2xml($arr, $level = 1)
    {
        $s = $level == 1 ? "<xml>" : '';
        foreach ($arr as $tagname => $value) {
            if (is_numeric($tagname)) {
                $tagname = $value['TagName'];
                unset($value['TagName']);
            }
            if (!is_array($value)) {
                $s .= "<{$tagname}>" . (!is_numeric($value) ? '<![CDATA[' : '') . $value . (!is_numeric($value) ? ']]>' : '') . "</{$tagname}>";
            } else {
                $s .= "<{$tagname}>" . $this->array2xml($value, $level + 1) . "</{$tagname}>";
            }
        }
        $s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);
        return $level == 1 ? $s . "</xml>" : $s;
    }

    /**
     * 签名
     * @return array
     */
    protected function makeSign($data)
    {
        //获取微信支付秘钥
        $key = $this->config['pay_apikey'];
        // 去空
        $data = array_filter($data);
        //签名步骤一：按字典序排序参数
        ksort($data);
        $string_a = http_build_query($data);
        $string_a = urldecode($string_a);
        //签名步骤二：在string后加入KEY
        //$config=$this->config;
        $string_sign_temp = $string_a . "&key=" . $key;
        //签名步骤三：MD5加密
        $sign = md5($string_sign_temp);
        // 签名步骤四：所有字符转为大写
        $result = strtoupper($sign);
        return $result;
    }

    /**
     * base64->img
     * @return array
     */
    public function base64_image_content($base64_image_content, $path)
    {
        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            $new_file = $path;
            if (!file_exists($new_file)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0755);
            }
            $file_name = time() . ".{$type}";
            $new_file = $new_file . $file_name;
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                return $file_name;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 随机生成32位字符串
     * @return array
     */
    public function getRangChar($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * post
     * @return array
     */
    protected function curl_post_ssl($url, $xmldata, $second = 30, $aHeader = array())
    {
        $ch = curl_init();
        //超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if (count($aHeader) >= 1) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmldata);

        $data = curl_exec($ch);
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            echo "call faild, errorCode:$error\n";
            curl_close($ch);
            return false;
        }
    }

}
