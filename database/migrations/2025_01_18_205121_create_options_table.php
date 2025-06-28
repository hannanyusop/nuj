<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->id(); // equivalent to int(10) unsigned AUTO_INCREMENT
            $table->string('name', 50); // equivalent to varchar(50)
            $table->longText('value'); // equivalent to longtext
            $table->dateTime('created_at'); // equivalent to datetime
            $table->dateTime('updated_at'); // equivalent to datetime
            $table->unique('name'); // unique index on the 'name' column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('options');
    }
}
