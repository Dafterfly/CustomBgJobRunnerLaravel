<?php
    
    use Monolog\Handler\NullHandler;
    use Monolog\Handler\StreamHandler;
    use Monolog\Handler\SyslogUdpHandler;
    use Monolog\Processor\PsrLogMessageProcessor;
    
    return [
    
    /*
        |--------------------------------------------------------------------------
        | Default Log Channel
        |--------------------------------------------------------------------------
        |
        | This option defines the default log channel that is utilized to write
        | messages to your logs. The value provided here should match one of
        | the channels present in the list of "channels" configured below.
        |
    */
    
    'default' => env('LOG_CHANNEL', 'stack'),
    
    /*
        |--------------------------------------------------------------------------
        | Deprecations Log Channel
        |--------------------------------------------------------------------------
        |
        | This option controls the log channel that should be used to log warnings
        | regarding deprecated PHP and library features. This allows you to get
        | your application ready for upcoming major versions of dependencies.
        |
    */
    
    'deprecations' => [
    'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
    'trace' => env('LOG_DEPRECATIONS_TRACE', false),
    ],
    
    /*
        |--------------------------------------------------------------------------
        | Log Channels
        |--------------------------------------------------------------------------
        |
        | Here you may configure the log channels for your application. Laravel
        | utilizes the Monolog PHP logging library, which includes a variety
        | of powerful log handlers and formatters that you're free to use.
        |
        | Available drivers: "single", "daily", "slack", "syslog",
        |                    "errorlog", "monolog", "custom", "stack"
        |
    */
    
    // config/logging.php

'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single', 'background_jobs'],
    ],
    'background_jobs' => [
        'driver' => 'single',
        'path' => storage_path('logs/background_jobs.log'),
        'level' => 'info',
    ],
    'background_jobs_errors' => [
        'driver' => 'single',
        'path' => storage_path('logs/background_jobs_errors.log'),
        'level' => 'error',
    ],
],

    
    ];
