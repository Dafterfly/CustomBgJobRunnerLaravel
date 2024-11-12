<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExampleController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/run-job', [App\Http\Controllers\ExampleController::class, 'runBackgroundJob']);


