<?php

namespace Modules\Backend\Facades;

use Illuminate\Support\Facades\Facade;


class LikeFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'LikeService';
    }
}