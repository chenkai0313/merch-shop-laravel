<?php

namespace Modules\Backend\Facades;

use Illuminate\Support\Facades\Facade;


class ProductFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ProductService';
    }
}