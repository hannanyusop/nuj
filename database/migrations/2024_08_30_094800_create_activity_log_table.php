<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_log', function (Blueprint $table) {
            $table->id(); // Auto-incrementing BIGINT primary key
            $table->string('log_name', 255)->nullable(); // Log name, nullable
            $table->text('description'); // Description, not nullable
            $table->unsignedBigInteger('subject_id')->nullable(); // Subject ID, nullable
            $table->string('subject_type', 255)->nullable(); // Subject type, nullable
            $table->unsignedBigInteger('causer_id')->nullable(); // Causer ID, nullable
            $table->string('causer_type', 255)->nullable(); // Causer type, nullable
            $table->json('properties')->nullable(); // Properties, stored as JSON, nullable
            $table->timestamps(); // created_at and updated_at timestamps, nullable

            // Indexes
            $table->index('log_name', 'activity_log_log_name_index');
            $table->index(['subject_id', 'subject_type'], 'subject');
            $table->index(['causer_id', 'causer_type'], 'causer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_log');
    }
}
