<?php
/**
 * 商品
 * Author: CK
 * Date: 2018/2/25
 */

namespace Modules\Backend\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $table = 'product';

    protected $primaryKey = 'prod_id';

    protected $fillable = array('prod_type', 'is_show', 'prod_title', 'prod_img', 'prod_content', 'prod_price','prod_sort','is_recommend');

    /**
     * 商品的添加
     * @return array
     */
    public static function productAdd($params)
    {
        $arr = ['prod_type', 'is_show', 'prod_title', 'prod_img', 'prod_content', 'prod_price','prod_sort','is_recommend'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return Product::create($data);
    }

    /**
     * 商品的修改
     * @return array
     */
    public static function productEdit($params)
    {
        $arr = ['prod_type', 'is_show', 'prod_title', 'prod_img', 'prod_content', 'prod_price','prod_sort','is_recommend'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return Product::where('prod_id', $params['prod_id'])->update($data);
    }

    /**
     * 商品的删除
     * @return array
     */
    public static function productDelete($params)
    {
        return Product::where('prod_id', $params['prod_id'])->delete();
    }

    /**
     * 商品的详情
     * @return array
     */
    public static function productDetail($params)
    {
        return Product::where('prod_id', $params['prod_id'])->first();
    }

    /**
     * 商品的列表
     * @return array
     */
    public static function productList($params)
    {
        $offset = ($params['page'] - 1) * $params['limit'];
        $data = Product::where(function ($query) use ($params) {
            if (!empty($params['prod_title'])) {
                return $query->where('prod_title', 'like', '%' . $params['prod_title'] . '%');
            }
        })
            ->where(function ($query) use ($params) {
                if (!is_null($params['is_show'])) {
                    return $query->where('is_show', $params['is_show']);
                }
            })
            ->where(function ($query) use ($params) {
                if (!is_null($params['is_recommend'])) {
                    return $query->where('is_recommend', $params['is_recommend']);
                }
            })
            ->skip($offset)
            ->take($params['limit'])
            ->orderByDesc('is_recommend')
            ->orderByDesc('prod_sort')
            ->orderByDesc('updated_at')
            ->get()
            ->toArray();
        return $data;
    }

    public static function productListCount($params)
    {
        $data = Product::where(function ($query) use ($params) {
            if (!empty($params['prod_title'])) {
                return $query->where('prod_title', 'like', '%' . $params['prod_title'] . '%');
            }
        })
            ->where(function ($query) use ($params) {
                if (!is_null($params['is_show'])) {
                    return $query->where('is_show', $params['is_show']);
                }
            })
            ->where(function ($query) use ($params) {
                if (!is_null($params['is_recommend'])) {
                    return $query->where('is_recommend', $params['is_recommend']);
                }
            })
            ->get()
            ->toArray();
        return count($data);
    }
}