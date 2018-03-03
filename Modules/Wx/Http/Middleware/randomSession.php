<?php

namespace Modules\Wx\HTTP\Middleware;

use Closure;
use Exception;
use Modules\Wx\Models\User;

class randomSession
{
    public function handle($request, Closure $next)
    {
        $params = $request->all();
        try {
            $user = User::userDetail($params);
            if ($user['token'] == $params['token'])
                return $next($request);
            else
                return ['code' => 90003, 'msg' => '访问不合法'];
        } catch (Exception $e) {
            return ['code' => 90003, 'msg' => '访问不合法'];
        }
    }


}
