<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ExampleJob;

// Require the helper file directly
require_once app_path('Support/helpers.php');

class ExampleController extends Controller
{
    public function runBackgroundJob()
    {
        runBackgroundJob(ExampleJob::class, 'handle', ['param1', 'param2']);
        return response()->json(['status' => 'Job is running in the background']);
    }
}
