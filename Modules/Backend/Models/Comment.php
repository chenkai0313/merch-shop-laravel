<?php
/**
 * 评论
 * Author: CK
 * Date: 2018/2/25
 */

namespace Modules\Backend\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $table = 'comment';

    protected $primaryKey = 'cmt_id';

    protected $fillable = array('user_id', 'cmt_content', 'prod_id');

    /**
     * 评论的添加
     * @return array
     */
    public static function commentAdd($params)
    {
        $arr = ['user_id', 'cmt_content', 'prod_id'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return Comment::create($data);
    }

    /**
     * 评论的修改
     * @return array
     */
    public static function commentEdit($params)
    {
        $arr = ['user_id', 'cmt_content', 'prod_id'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return Comment::where('cmt_id', $params['cmt_id'])->update($data);
    }

    /**
     * 管理员评论的删除
     * @return array
     */
    public static function commentDelete($params)
    {
        return Comment::where('cmt_id', $params['cmt_id'])->delete();
    }

    //todo 用户评论的删除

    /**
     * 用户评论的删除
     * @return array
     */
    public static function commentDeleteUser($params)
    {
        return Comment::where('prod_id', $params['prod_id'])->where('user_id', $params['user_id'])
            ->where('cmt_id', $params['cmt_id'])->delete();
    }

    /**
     * 评论的详情
     * @return array
     */
    public static function commentDetail($params)
    {
        return Comment::where('cmt_id', $params['cmt_id'])->first();
    }

    /**
     * 评论的列表
     * @return array
     */
    public static function commentList($params)
    {
        $offset = ($params['page'] - 1) * $params['limit'];
        $data = Comment::leftJoin('user', 'user.user_id', '=', 'comment.user_id')
            ->leftJoin('product', 'product.prod_id', '=', 'comment.prod_id')
            ->select('user.avatarUrl', 'user.user_id', 'user.nick_name', 'product.prod_id', 'product.prod_title', 'comment.*')
            ->where('comment.prod_id', $params['prod_id'])
            ->where(function ($query) use ($params) {
                if (!empty($params['prod_title'])) {
                    return $query->where('product.prod_title', 'like', '%' . $params['prod_title'] . '%');
                }
            })
            ->where(function ($query) use ($params) {
                if (!empty($params['nick_name'])) {
                    return $query->where('user.nick_name', 'like', '%' . $params['nick_name'] . '%');
                }
            })
            ->skip($offset)
            ->take($params['limit'])
            ->orderByDesc('comment.created_at')
            ->get()
            ->toArray();
        return $data;
    }

    public static function commentListCount($params)
    {
        $data = Comment::leftJoin('user', 'user.user_id', '=', 'comment.user_id')
            ->leftJoin('product', 'product.prod_id', '=', 'comment.prod_id')
            ->select('user.avatarUrl', 'user.user_id', 'user.nick_name', 'product.prod_id', 'product.prod_title', 'comment.*')
            ->where('comment.prod_id', $params['prod_id'])
            ->where(function ($query) use ($params) {
                if (!empty($params['prod_title'])) {
                    return $query->where('product.prod_title', 'like', '%' . $params['prod_title'] . '%');
                }
            })
            ->where(function ($query) use ($params) {
                if (!empty($params['nick_name'])) {
                    return $query->where('user.nick_name', 'like', '%' . $params['nick_name'] . '%');
                }
            })
            ->orderByDesc('comment.created_at')
            ->get()
            ->toArray();
        return count($data);
    }
}