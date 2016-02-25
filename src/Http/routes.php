<?php

if (Config::get('patrol.enable_webhooks')) {
    Route::group(['prefix' => 'patrolserver', 'namespace' => 'PatrolServer\Patrol\Http\Controllers'], function () {
        Route::post('webhook', 'WebhookController@incoming');
        Route::get('webhook', 'WebhookController@info');
    });
}
