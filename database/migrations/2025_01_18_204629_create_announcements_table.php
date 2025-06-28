<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id(); // equivalent to bigint(20) unsigned AUTO_INCREMENT
            $table->enum('area', ['frontend', 'backend'])->nullable(); // equivalent to enum('frontend', 'backend')
            $table->enum('type', ['info', 'danger', 'warning', 'success'])->default('info'); // equivalent to enum with default value
            $table->text('message'); // equivalent to text
            $table->boolean('enabled')->default(true); // equivalent to tinyint(1) with default '1'
            $table->timestamp('starts_at')->nullable(); // equivalent to timestamp NULL DEFAULT NULL
            $table->timestamp('ends_at')->nullable(); // equivalent to timestamp NULL DEFAULT NULL
            $table->timestamps(); // equivalent to created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('announcements');
    }
}
