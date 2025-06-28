<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // equivalent to VARCHAR(255) for session id
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // nullable foreign key to users table
            $table->text('payload'); // equivalent to TEXT to store session data
            $table->integer('last_activity'); // stores last activity timestamp as an integer
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps(0); // equivalent to created_at and updated_at columns with no fractional seconds
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessions');
    }
}
