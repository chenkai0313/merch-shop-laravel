<?php
/**
 * Created by PhpStorm.
 * User: CK
 * Date: 2017/11/16
 * Time: 14:43
 */

namespace Modules\Backend\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FileUploadController extends Controller
{
    /**
     * 单文件上传
     */
    public function fileUpLoad(Request $request)
    {
        $files = is_null($request->file('files')) ? '' : $request->file('files');
        return $files;
        $upload = uploadFiles($files);//上传文件
        $data['file_name'] = $upload['file_name'];
        $data['file_path'] = $upload['file_path'];
        $data['host_url'] = $upload['host_url'];
        return ['code' => 1, 'data' => $data];
    }

    /**
     * 多文件上传
     */
    public function fileUpLoadAll(Request $request)
    {
        $files = is_null($request->file('files')) ? '' : $request->file('files');
        $upload = uploadFilesAll($files);//上传文件
        $data['file_name'] = $upload['file_name'];
        $data['file_path'] = $upload['file_path'];
        $data['host_url'] = $upload['host_url'];
        return ['code' => 1, 'data' => $data];
    }

    /**
     * 七牛单文件上传
     */
    public function qiniuUpload(Request $request)
    {
        $disk = \Storage::disk('qiniu'); //使用七牛云上传
        $time = time();
        $files = is_null($request->file('files')) ? '' : $request->file('files');
        $filename = $disk->put($time, $files);//上传
        if (!$filename) {
            return ['code' => 90002, 'msg' => '上传失败'];
        }
        $img_url = $disk->getDriver()->downloadUrl($filename); //获取下载链接
        return ['code' => 1, 'data' => $img_url];
    }

    /**
     * 七牛多文件上传
     */
    public function qiniuUploadAll(Request $request)
    {
        $disk = \Storage::disk('qiniu'); //使用七牛云上传
        $time = time();
        $files = is_null($request->file('files')) ? '' : $request->file('files');
        for ($i = 0; $i < count($files); $i++) {
            $filename = $disk->put($time, $files[$i]);//上传
            if (!$filename) {
                return ['code' => 90002, 'msg' => '上传失败'];
            }
            $img_url['list'][$i] = $disk->getDriver()->downloadUrl($filename);
        }
        return ['code' => 1, 'data' => $img_url];
    }
}