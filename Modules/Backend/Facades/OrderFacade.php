<?php

namespace Modules\Backend\Facades;

use Illuminate\Support\Facades\Facade;


class OrderFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'OrderService';
    }
}