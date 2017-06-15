<?php

namespace Silvanite\Agencms\Facades;

use Illuminate\Support\Facades\Facade;

class ConfigFacade extends Facade {

    /**
     * Get the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'AgencmsConfig';
    }
}