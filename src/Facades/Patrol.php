<?php namespace PatrolServer\Patrol\Facades;

use Illuminate\Support\Facades\Facade;

class Patrol extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'ps.sdk'; }

}
