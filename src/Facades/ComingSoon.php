<?php

namespace MBonaldo\ComingSoon\Facades;

use Illuminate\Support\Facades\Facade;

class ComingSoon extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'comingsoon';
    }
}
