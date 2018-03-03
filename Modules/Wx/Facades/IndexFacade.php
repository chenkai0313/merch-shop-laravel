<?php

namespace Modules\Wx\Facades;

use Illuminate\Support\Facades\Facade;


class IndexFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return  'IndexService';
    }
}