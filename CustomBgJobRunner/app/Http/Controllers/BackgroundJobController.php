<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Helpers\BackgroundJobRunner;
use App\Models\JobLog; // Assuming you have a JobLog model to store job data
use Illuminate\Support\Facades\File;

class BackgroundJobController extends Controller
{
    /**
     * Display a listing of background job logs.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch logs from database or log files, depending on how you've stored them
        $logs = JobLog::all(); // Retrieve all job logs from the JobLog model
        
        return view('background-jobs.index', compact('logs'));
    }

    /**
     * Retry a specific job by ID.
     *
     * @param  int  $jobId
     * @return \Illuminate\Http\RedirectResponse
     */
     public function retry($jobId)
    {
        // Retrieve the job data from logs or database using the job ID
        $jobLog = JobLog::find($jobId);

        if (!$jobLog) {
            return redirect()->route('background-jobs.index')->with('error', 'Job not found.');
        }

        // Re-run the job using the BackgroundJobRunner
        $jobRunner = new BackgroundJobRunner(
            $jobLog->class, 
            $jobLog->method, 
            $jobLog->parameters, 
            $jobLog->maxRetries, 
            $jobLog->retryDelay
        );

        $jobRunner->execute(); // Retry the job execution

        // Optionally log the retry action
        Log::info("Retried job: {$jobLog->class}@{$jobLog->method}");

        return redirect()->route('background-jobs.index')->with('success', 'Job retried successfully.');
    }

    /**
     * Cancel a specific job by ID.
     *
     * @param  int  $jobId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel($jobId)
    {
        // Fetch job log entry by ID to mark it as canceled
        $jobLog = JobLog::find($jobId);

        if (!$jobLog) {
            return redirect()->route('background-jobs.index')->with('error', 'Job not found.');
        }

        // Update job status to 'canceled' (in the JobLog table)
        $jobLog->status = 'canceled';
        $jobLog->save();

        // Optionally log the cancel action
        Log::info("Canceled job: {$jobLog->class}@{$jobLog->method}");

        return redirect()->route('background-jobs.index')->with('success', 'Job canceled successfully.');
    }

    /**
     * Helper method to fetch job logs for the dashboard.
     *
     * @return array
     */
    protected function fetchJobLogs()
    {
        // Fetch job logs from a log file (as an alternative if using files)
        if (File::exists(storage_path('logs/background_jobs.log'))) {
            $logFile = File::get(storage_path('logs/background_jobs.log'));
            $logs = explode("\n", $logFile);
            return array_filter($logs); // Filter empty lines
        }

        return []; // Return an empty array if the file does not exist
    }
}
