<?php namespace PatrolServer\Patrol\Services;

use PatrolServer\Patrol\Facades\Patrol as Sdk;
use URL;

class Server {

    public static function current() {
        $url = URL::to('/');
        $servers = Sdk::servers();

        $host = parse_url($url, PHP_URL_HOST);
        $host = preg_replace("/^www\./", "", $host);
        $host = preg_replace("/[^a-zA-Z0-9\.\-_]/", "", $host);

        // If host is a single name, eg; localhost, use a dummy extension.
        if (strpos($host, '.') === FALSE)
            $host .= '.ps';

        // Lookup if our server is present.
        foreach ($servers as $s)
            if ($s->name === $host)
                return $s;

        // At this point, no valid server is found and we can create a new server.
        $server = Sdk::server();
        $server->domain = $host;
        $server->save();

        return $server;
    }

}
