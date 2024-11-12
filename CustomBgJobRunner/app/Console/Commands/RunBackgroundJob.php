<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\BackgroundJobRunner;

class RunBackgroundJob extends Command
{
    protected $signature = 'run:background-job {class} {method} {parameters?*}';
    protected $description = 'Run a background job';

    public function handle()
    {
        $class = $this->argument('class');
        $method = $this->argument('method');
        $parameters = $this->argument('parameters') ?: [];

        $jobRunner = new BackgroundJobRunner($class, $method, $parameters);
        $jobRunner->execute();
    }
}
