<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficesTable extends Migration
{

    public function up()
    {
        Schema::create('offices', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('code', 50)->unique(); // Code with unique constraint
            $table->string('name', 255); // Name of the office
            $table->boolean('is_drop_point')->default(false); // Drop point indicator with default value
            $table->text('address')->nullable(); // Address, nullable
            $table->string('location', 50)->nullable(); // Location, nullable
            $table->text('operation_day')->nullable(); // Operation day, nullable
            $table->timestamps(); // created_at and updated_at fields
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offices');
    }
}
