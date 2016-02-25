<?php namespace PatrolServer\Patrol\Console\Commands;

use Illuminate\Console\Command;
use PatrolServer\Patrol\Services\CollectSoftware;
use PatrolServer\Patrol\Services\SoftwareStorer;
use Symfony\Component\Console\Input\InputOption;

class Run extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'patrol:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store software in this Laravel instance in a PatrolServer bucket, which can then be used to scan for outdated versions.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $scan_after = $this->option('scan');

        // Collect software on this Laravel instance.
        $collector = new CollectSoftware;
        $software = $collector->softwareList();

        // Store the software in a bucket and create a server if needed.
        $store = new SoftwareStorer($software);
        $store->save($scan_after);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions() {
        return [
            ['scan', null, InputOption::VALUE_OPTIONAL, 'Scan the server immediately.', null],
        ];
    }

}
