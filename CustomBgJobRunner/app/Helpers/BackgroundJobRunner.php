<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\JobLog; // Ensure you have the JobLog model imported if you are using it

class BackgroundJobRunner
{
    protected $class;
    protected $method;
    protected $parameters;
    protected $maxRetries;
    protected $retryDelay;
    protected $jobLog;

    public function __construct($class, $method, $parameters = [], $maxRetries = 3, $retryDelay = 5)
    {
        $this->class = $class;
        $this->method = $method;
        $this->parameters = $parameters;
        $this->maxRetries = $maxRetries ?? 3; // Default to 3 if not provided
        $this->retryDelay = $retryDelay ?? 5; // Default to 5 if not provided

        // Create a new job log entry
        $this->jobLog = JobLog::create([
            'class' => $this->class,
            'method' => $this->method,
            'parameters' => json_encode($this->parameters),
            'status' => 'pending',
            'max_retries' => $this->maxRetries,
            'retry_delay' => $this->retryDelay,
        ]);
    }


    public function execute()
    {
        // Mark the job as 'running'
        $this->jobLog->update(['status' => 'running']);

        $attempts = 0;
        $success = false;

        while ($attempts < $this->maxRetries && !$success) {
            try {
                $attempts++;
                $this->jobLog->update(['attempts' => $attempts]);

                $instance = app($this->class);
                call_user_func_array([$instance, $this->method], $this->parameters);

                $success = true;
                $this->jobLog->update(['status' => 'completed']);
            } catch (Exception $e) {
                Log::error("Job execution failed on attempt {$attempts}: {$e->getMessage()}");
                $this->jobLog->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
                sleep($this->retryDelay);
            }
        }

        if (!$success) {
            $this->jobLog->update(['status' => 'failed', 'error_message' => 'Max retries exceeded']);
        }

        return $success;
    }
}
