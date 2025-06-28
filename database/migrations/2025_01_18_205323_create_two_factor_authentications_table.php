<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTwoFactorAuthenticationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('two_factor_authentications', function (Blueprint $table) {
            $table->id(); // equivalent to bigint(20) unsigned AUTO_INCREMENT
            $table->string('authenticatable_type'); // equivalent to varchar(255)
            $table->unsignedBigInteger('authenticatable_id'); // equivalent to bigint(20) unsigned
            $table->binary('shared_secret'); // equivalent to blob
            $table->timestamp('enabled_at')->nullable(); // equivalent to timestamp NULL DEFAULT NULL
            $table->string('label'); // equivalent to varchar(255)
            $table->unsignedTinyInteger('digits')->default(6); // equivalent to tinyint(3) unsigned with default '6'
            $table->unsignedTinyInteger('seconds')->default(30); // equivalent to tinyint(3) unsigned with default '30'
            $table->unsignedTinyInteger('window')->default(0); // equivalent to tinyint(3) unsigned with default '0'
            $table->string('algorithm', 16)->default('sha1'); // equivalent to varchar(16) with default 'sha1'
            $table->json('recovery_codes')->nullable(); // equivalent to json with default NULL
            $table->timestamp('recovery_codes_generated_at')->nullable(); // equivalent to timestamp NULL DEFAULT NULL
            $table->json('safe_devices')->nullable(); // equivalent to json with default NULL
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
        Schema::dropIfExists('two_factor_authentications');
    }
}
