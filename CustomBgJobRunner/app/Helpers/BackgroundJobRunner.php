<?php
    
    namespace App\Helpers;
    
    use Exception;
    use Illuminate\Support\Facades\Log;
    
    class BackgroundJobRunner
    {
        protected $class;
        protected $method;
        protected $parameters;
        protected $maxRetries = 3;
        protected $retryDelay = 5;
        protected $delay = 0; // Default delay to 0
        
        public function __construct($class, $method, $parameters = [], $maxRetries = null, $retryDelay = null, $delay = 0)
        {
            $this->class = $class;
            $this->method = $method;
            $this->parameters = $parameters;
            $this->maxRetries = $maxRetries ?? $this->maxRetries;
            $this->retryDelay = $retryDelay ?? $this->retryDelay;
            $this->delay = $delay;
        }
        
        public function execute()
        {
            if ($this->delay > 0) {
                Log::info("Delaying job execution by {$this->delay} seconds.");
                sleep($this->delay);
            }
            
            if (!$this->isAuthorized()) {
                Log::error("Unauthorized job execution attempt for class: {$this->class}");
                return false;
            }
            
            $attempts = 0;
            $success = false;
            
            while ($attempts < $this->maxRetries && !$success) {
                try {
                    $attempts++;
                    $instance = app($this->class);
                    call_user_func_array([$instance, $this->method], $this->parameters);
                    $success = true;
                    Log::info("Job successfully executed: {$this->class}@{$this->method}");
                    } catch (Exception $e) {
                    Log::error("Job execution failed on attempt {$attempts}: {$e->getMessage()}");
                    sleep($this->retryDelay);
                }
            }
            
            if (!$success) {
                Log::error("Job failed after {$this->maxRetries} attempts: {$this->class}@{$this->method}");
            }
            
            return $success;
        }
        
        protected function isAuthorized()
        {
            $approvedClasses = config('background_jobs.approved_classes', []);
            return isset($approvedClasses[$this->class]) && in_array($this->method, $approvedClasses[$this->class]);
        }
    }
