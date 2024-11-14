<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_logs', function (Blueprint $table) {
            $table->id();
            $table->string('class'); // Job class name
            $table->string('method'); // Method name
            $table->json('parameters')->nullable(); // Parameters passed to the method
            $table->enum('status', ['pending', 'running', 'completed', 'failed', 'canceled'])->default('pending');
            $table->integer('max_retries')->default(3);
            $table->integer('retry_delay')->default(5); // Delay between retries in seconds
            $table->integer('attempts')->default(0); // Track number of attempts
            $table->text('error_message')->nullable(); // Store error messages if job fails
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_logs');
    }
}
