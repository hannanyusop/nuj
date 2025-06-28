<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); // equivalent to bigint(20) unsigned AUTO_INCREMENT
            $table->enum('type', ['admin', 'user']); // equivalent to enum('admin', 'user')
            $table->string('name'); // equivalent to varchar(255)
            $table->string('guard_name'); // equivalent to varchar(255)
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
        Schema::dropIfExists('roles');
    }
}
