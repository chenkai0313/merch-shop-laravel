<?php

namespace Modules\Wx\Facades;

use Illuminate\Support\Facades\Facade;


class UserFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return  'UserService';
    }
}