<?php

namespace Modules\Backend\Facades;

use Illuminate\Support\Facades\Facade;


class AdvFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'AdvService';
    }
}