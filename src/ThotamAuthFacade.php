<?php

namespace Thotam\ThotamAuth;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Thotam\ThotamAuth\Skeleton\SkeletonClass
 */
class ThotamAuthFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'thotam-auth';
    }
}
