<?php
/**
 * 商家配置
 * Author: CK
 * Date: 2018/2/25
 */

namespace Modules\Backend\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{

    protected $table = 'config';

    protected $primaryKey = 'config_id';

    protected $fillable = array('config_content', 'config_type');

    /**
     * PC,WX端配置修改
     * @return array
     */
    public static function configEdit($params,$data)
    {
        return Config::where('config_type', $params['config_type'])->update($data);
    }

    /**
     * 配置详情
     * @return array
     */
    public static function configDetail($params)
    {
        $data = Config::where('config_type', $params['config_type'])->first();
        $data['config_content'] = unserialize($data['config_content']);
        return $data;
    }
}