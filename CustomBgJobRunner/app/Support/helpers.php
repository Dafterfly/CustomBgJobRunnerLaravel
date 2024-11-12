<?php

use App\Helpers\BackgroundJobRunner;

function runBackgroundJob($class, $method, $parameters = [], $maxRetries = 3, $retryDelay = 5)
{
    $jobRunner = new BackgroundJobRunner($class, $method, $parameters, $maxRetries, $retryDelay);
    
    // Check the OS and run in the background accordingly
    if (strncasecmp(PHP_OS, 'WIN', 3) == 0) {
        pclose(popen("start /B php " . base_path("artisan run:background-job {$class} {$method}"), 'r'));
    } else {
        exec("php " . base_path("artisan run:background-job {$class} {$method} > /dev/null &"));
    }
}
