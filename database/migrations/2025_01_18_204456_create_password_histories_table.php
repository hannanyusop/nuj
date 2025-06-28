<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_histories', function (Blueprint $table) {
            $table->id(); // equivalent to bigint(20) unsigned AUTO_INCREMENT
            $table->string('model_type'); // equivalent to varchar(255)
            $table->unsignedBigInteger('model_id'); // equivalent to bigint(20) unsigned
            $table->string('password'); // equivalent to varchar(255)
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
        Schema::dropIfExists('password_histories');
    }
}
