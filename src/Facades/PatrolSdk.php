<?php namespace PatrolServer\PatrolSdk\Facades;

use Illuminate\Support\Facades\Facade;

class PatrolSdk extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'patrolserver.laravel-patrol'; }

}
