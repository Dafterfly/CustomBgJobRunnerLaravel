<?php

return [
    'retry' => [
        'max_attempts' => 3,
        'delay_seconds' => 5,
    ],
    'approved_classes' => [
        // Example of approved classes and methods:
        'App\Jobs\ExampleJob' => ['handle'],
        // Add more classes and their approved methods as needed
    ],
];
