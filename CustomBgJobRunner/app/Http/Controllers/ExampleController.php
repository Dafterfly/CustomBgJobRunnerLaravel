<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ExampleJob;

class ExampleController extends Controller
{
    public function runBackgroundJob()
    {
        // Use the fully qualified helper function name if needed
        runBackgroundJob(ExampleJob::class, 'handle', ['param1', 'param2']);
        return response()->json(['status' => 'Job is running in the background']);
    }
}
