<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordResetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email'); // equivalent to varchar(255)
            $table->string('token'); // equivalent to varchar(255)
            $table->timestamp('created_at')->nullable(); // equivalent to timestamp with nullable default NULL
            $table->index('email'); // equivalent to KEY password_resets_email_index (index on email column)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_resets');
    }
}
