<?php namespace PatrolServer\Patrol\Services;

use PatrolServer\Patrol\Facades\Patrol as Sdk;
use URL;

class SoftwareStorer {

    private $bucket_name = 'LaravelSDK';
    private $software = [];
    private $expire = 86600;

    public function __construct($software) {
        $this->software = $software;
    }

    private function server() {
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

    public function save($scan_after) {
        $server = Server::current();

        $bucket = $server->bucket($this->bucket_name);

        $bucket->software = $this->software;
        $bucket->expire = $this->expire;

        $bucket->save();

        // If we specified a scan after the bucket store, scan after
        if ($scan_after)
            $server->scan();
    }

}
