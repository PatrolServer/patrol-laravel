<?php namespace PatrolServer\Patrol\Http\Controllers;

use Illuminate\Routing\Controller;
use Response;
use URL;
use PatrolServer\Patrol\Facades\Patrol as Sdk;
use PatrolServer\Patrol\Services\Server;
use Input;
use PatrolServer\Patrol\Services\Composer;

class WebhookController extends Controller {

    public function info() {
        if (Input::get('composer'))
            $this->updateComposer();
        else
            print 'Add "' . URL::to('patrolserver/webhook') . '" in your PatrolServer dashboard.';

        exit;
    }

    public function incoming() {
        Sdk::webhook('new_server_issues', function ($event) {
            $server_id = array_get($event, 'server_id');

            // Check whether this webhook is meant for us or not.
            $current = Server::current();
            if ($server_id !== $current->id)
                return;

            $this->updateComposer();
        });
    }

    private function updateComposer() {
        $path = app_path() . '/../';
        with(new Composer($path))->update();
    }

}
