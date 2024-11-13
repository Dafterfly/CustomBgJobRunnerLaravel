# Laravel Custom Background Job Runner

This project provides a custom background job runner for Laravel, enabling you to run PHP classes as background jobs independently of Laravel's built-in queue system. This solution is designed with scalability, error handling, and security in mind.

---

## Features

- **Background Job Execution**: Run any specified class method in the background.
- **Cross-Platform Compatibility**: Supports both Windows and Unix-based systems for background job execution.
- **Error Handling**: Catches exceptions, logs errors, and provides a retry mechanism.
- **Logging**: Logs job status and errors, including retry attempts.
- **Security**: Allows only pre-approved classes and methods to be run in the background.
- **Configuration Options**: Easily configure retry attempts, delay between retries, and approved classes.

---

## Setup and Installation

1. **Clone the repository**:
   
   ```bash
   git clone <repository-url>
   cd <repository-folder>
   ```

2. **Install Laravel dependencies**:
   
   ```bash
   composer install
   ```

3. **Configuration**:
   
   - Copy `.env.example` to `.env` and configure your database and other settings as needed.
   - Run migrations if necessary for other parts of the Laravel application:
     
     ```bash
     php artisan migrate
     ```

4. **Set Up Configuration for Background Job Runner**:
   
   - Create a configuration file for the background job runner at `config/background_jobs.php`:
     
     ```php
     <?php
     
     return [
         'retry' => [
             'max_attempts' => 3,
             'delay_seconds' => 5,
         ],
         'approved_classes' => [
             // Example: allow ExampleJob's handle method
             'App\Jobs\ExampleJob' => ['handle'],
             // Add other approved classes and methods here
         ],
     ];
     ```

5. **Custom Logging Configuration**:
   
   - Open `config/logging.php` and add a new logging channel for background job errors:
     
     ```php
     'channels' => [
         // Other channels...
         'background_jobs' => [
             'driver' => 'single',
             'path' => storage_path('logs/background_jobs_errors.log'),
             'level' => 'error',
         ],
     ],
     ```

6. **Adding Helper Functions**:
   
   - Add a `runBackgroundJob` helper function in `app/Support/helpers.php`, if not already present:
     
     ```php
     <?php
     
     use App\Helpers\BackgroundJobRunner;
     
     function runBackgroundJob($class, $method, $parameters = [], $maxRetries = 3, $retryDelay = 5)
     {
         $jobRunner = new BackgroundJobRunner($class, $method, $parameters, $maxRetries, $retryDelay);
         $jobRunner->execute();
     }
     ```

---

## Usage

### Running a Background Job

Use the `runBackgroundJob` helper function to execute a job in the background. This function accepts the following parameters:

```php
runBackgroundJob($class, $method, $parameters = [], $maxRetries = 3, $retryDelay = 5);
```

- **$class**: The fully-qualified class name of the job you want to run.
- **$method**: The method in the class to execute.
- **$parameters**: An array of parameters to pass to the method.
- **$maxRetries**: Optional. Sets the maximum retry attempts (default is 3).
- **$retryDelay**: Optional. Sets the delay between retry attempts in seconds (default is 5).

**Example**

```php
runBackgroundJob(App\Jobs\ExampleJob::class, 'handle', ['param1', 'param2']);
```

This command will execute the `handle` method of `App\Jobs\ExampleJob` with `param1` and `param2` as arguments.

## Configuration

### Retry Settings

You can configure the retry attempts and delay between retries in `config/background_jobs.php`.

```php
'retry' => [
    'max_attempts' => 3,
    'delay_seconds' => 5,
],

```

### Approved Classes and Methods

For security, only pre-approved classes and methods can be executed. Define these in `config/background_jobs.php`:

```php
'approved_classes' => [
    'App\Jobs\ExampleJob' => ['handle'],
],
```

## Logging

- **Job Logs**: Job execution statuses (e.g., "Job successfully executed") are logged in the standard `laravel.log` file.
- **Error Logs**: Errors and unauthorized attempts are logged in `storage/logs/background_jobs_errors.log`.

## Testing

### Basic Tests

1. **Run a Valid Job**:
   - Use `runBackgroundJob` with an approved class and method and confirm it runs successfully.
2. **Test Unauthorized Job Execution**:
   - Attempt to run a non-approved class and check `background_jobs_errors.log` for unauthorized attempt logging.

## Assumptions and Limitations

- **Job Execution Security**: Only classes/methods listed in `approved_classes` are allowed to execute. Additional security measures may be required in production environments.
- **Queue Priority and Delays**: Basic priority and delay handling can be added as needed, though this implementation does not include complex scheduling.
