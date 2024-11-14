<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExampleController;
use App\Http\Controllers\BackgroundJobController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/run-job', [App\Http\Controllers\ExampleController::class, 'runBackgroundJob']);
Route::get('/background-jobs', [BackgroundJobController::class, 'index'])->name('background-jobs.index');
Route::post('/background-jobs/retry/{jobId}', [BackgroundJobController::class, 'retry'])->name('background-jobs.retry');
Route::post('/background-jobs/cancel/{jobId}', [BackgroundJobController::class, 'cancel'])->name('background-jobs.cancel');


