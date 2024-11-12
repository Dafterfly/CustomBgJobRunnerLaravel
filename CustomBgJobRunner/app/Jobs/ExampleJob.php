// app/Jobs/ExampleJob.php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;

class ExampleJob
{
    public function handle()
    {
        Log::info('ExampleJob is being processed');
        // Example background task logic here
        Log::info('Hello world!');
    }
}
