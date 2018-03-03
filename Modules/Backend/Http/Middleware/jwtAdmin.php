<?php

namespace Modules\Backend\HTTP\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Entrust;

class jwtAdmin
{
    public function handle($request, Closure $next)
    {

        $this->registerJWTConfig();
        #获取当前路由器信息
        $request_info = request()->route()->getAction();
        $arr = explode('@',$request_info['controller']);
        #若非登录页面，则验证JWT与RBAC
        if($arr['1']!='adminLogin'){
            #验证登录   JWT
            try {
                $payload = JWTAuth::parseToken()->getPayload();
                $from = $payload->get('from');
                if (!$from=='admin' || !$user = JWTAuth::parseToken()->authenticate()) {
                    return ['code' => 10094,'msg' => '找不到该管理员'];
                }
            } catch (Exception $e) {
                if ($e instanceof  TokenInvalidException)
                    return ['code'=>10091,'msg'=>'token信息不合法'];
                else if ($e instanceof TokenExpiredException) {
                    return ['code'=>10092,'msg'=>'登录信息过期'];
                }else{
                    return ['code'=>10093,'msg'=>'登录验证失败'];
                }
            }
        }
        return $next($request);
    }


    protected function registerJWTConfig()
    {
        \Config::set('jwt.user' , 'Modules\Backend\Models\Admin');
        \Config::set('auth.providers.users.table', 'admins');
        \Config::set('auth.providers.users.model', \Modules\Backend\Models\Admin::class);
        \Config::set('jwt.identifier' , 'admin_id');
        \Config::set('cache.default','array');//RBAC
    }

}
