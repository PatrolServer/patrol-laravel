<?php namespace PatrolServer\Patrol\Services;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Composer {

    private $composer_path = '';
    private $command = 'composer update';

    private $logger = null;

    public function __construct($composer_path) {
        $this->composer_path = $composer_path;
    }

    public function update() {
        $output = shell_exec('cd ' . $this->composer_path . ' && ' . $this->command);
        $this->log($output);
    }

    private function log($data) {
        if (is_null($this->logger)) {
            $this->logger = new Logger('_Composer_Log');

            $streamHandler = new StreamHandler(storage_path('/logs/patrol_sdk.log'), Logger::INFO);
            $this->logger->pushHandler($streamHandler);
        }

        $this->logger->addInfo($data);
    }

}
