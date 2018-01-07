<?php

namespace Rigorbb\FastAuthLinks;

use Illuminate\Support\Facades\Facade;

class FastAuthLinkFacade extends Facade
{
    /**
     * The name of the binding in the IoC container.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'FastAuthLink';
    }
}