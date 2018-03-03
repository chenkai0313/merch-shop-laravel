<?php
/**
 * Created by PhpStorm.
 * User: CK
 * Date: 2017/11/15
 * Time: 14:07
 */
return [
    'report' => [
        'report-val' => [
            'report_name' => '公文名称',
            'report_title' => '公文标题',
            'to_admin_id' => '公文接收人',
            'scan_times' => '扫描次数',
            'deal_time' => '建议完成时间',
            'risk_level' => '风险等级',
        ],
        'report-key' => [
            'integer' => ':attribute必须为整数',
            'required' => ':attribute必填',
            'regex' => ':attribute格式不正确',
            'unique' => ':attribute已被注册',
        ],
        #添加公文
        'report-add' => [
            'report_name' => 'required',
            'report_title' => 'required',
            'to_admin_id' => 'required',
            'scan_times' => 'required',
            'deal_time' => 'required',
            'risk_level' => 'required',
        ]
    ],
    #服务器信息
    'serverinfo' => [
        'serverinfo-val' => [
            'svr_title' => '服务器名称',
            'web_id' => '网站id',
            'admin_id' => '用户id',
            'svr_ip' => '服务器ip',
        ],
        'serverinfo-key' => [
            'integer' => ':attribute必须为整数',
            'required' => ':attribute必填',
            'regex' => ':attribute格式不正确',
            'unique' => ':attribute是唯一的',
        ],
        #添加
        'serverinfo-add' => [
            'web_id' => 'required',
            'admin_id' => 'required',
            'svr_title' => 'required',
            'svr_ip' => array('regex: /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/', 'required'),
        ],
    ],
    #服务器信息
    'weixin' => [
        'weixin-val' => [
            'wx_username' => '微信帐号',
            'wx_password' => '微信密码',
            'wx_name' => '公众号名称',
            'orginal_id' => '原始id',
            'thumb' => '公众号名logo',
            'qrcode' => '公众号二维码',
            'token' => '公众号token',
            'appid' => 'key',
            'appsecret' => '公众号凭证',
            'web_url' => '对应网站域名',
            'admin_id' => '用户id',
            'wx_id' => 'id',
        ],
        'weixin-key' => [
            'integer' => ':attribute必须为整数',
            'required' => ':attribute必填',
            'regex' => ':attribute格式不正确',
            'unique' => ':attribute是唯一的',
        ],
        #添加
        'weixin-add' => [
            'wx_username' => 'required',
            'wx_name' => 'required',
            'orginal_id' => 'required',
            'thumb' => 'required',
            'qrcode' => 'required',
            'appid' => 'required',
            'appsecret' => 'required',
        ],
        #编辑
        'weixin-edit' => [
            'wx_id' => 'required',
            'wx_username' => 'required',
            'wx_name' => 'required',
            'orginal_id' => 'required',
            'thumb' => 'required',
            'qrcode' => 'required',
            'appid' => 'required',
            'appsecret' => 'required',
        ],
    ],
    #轮播图信息
    'slide' => [
        'slide-val' => [
            'slide_id' => 'id',
            'slide_title' => '轮播图标题',
            'slide_url' => '轮播图链接地址',
            'slide_thumb' => '轮播图图片路径',
            'display' => '是否展示',
        ],
        'slide-key' => [
            'integer' => ':attribute必须为整数',
            'required' => ':attribute必填',
            'regex' => ':attribute格式不正确',
            'unique' => ':attribute是唯一的',
        ],
        #添加
        'slide-add' => [
            'slide_title' => 'required',
//            'slide_url' => 'required',
            'slide_thumb' => 'required',
            'display' => 'required',
        ],
    ]

];