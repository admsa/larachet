<?php namespace Admsa\Larachet;

use Illuminate\Support\Facades\Facade;

class LarachetFacade extends Facade {

    /**
     * Facade Accessor
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'larachet';
    }
}
