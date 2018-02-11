<?php

namespace Silvanite\Agencms\Facades;

use Illuminate\Support\Facades\Facade;

class RenderEngine extends Facade
{

    /**
     * Get the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'agencms-render-engine';
    }
}
