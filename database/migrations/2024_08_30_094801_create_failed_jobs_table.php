<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFailedJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id(); // Auto-incrementing BIGINT primary key
            $table->text('connection'); // Connection, not nullable
            $table->text('queue'); // Queue, not nullable
            $table->longText('payload'); // Payload, not nullable
            $table->longText('exception'); // Exception, not nullable
            $table->timestamp('failed_at')->useCurrent(); // Failed at, not nullable, defaults to current timestamp
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('failed_jobs');
    }
}
