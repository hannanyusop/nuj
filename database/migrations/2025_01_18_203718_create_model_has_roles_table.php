<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelHasRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id'); // Role ID, unsigned BIGINT
            $table->string('model_type', 255); // Model type, VARCHAR(255)
            $table->unsignedBigInteger('model_id'); // Model ID, unsigned BIGINT

            // Primary key
            $table->primary(['role_id', 'model_id', 'model_type']);

            // Index for model_id and model_type
            $table->index(['model_id', 'model_type'], 'model_has_roles_model_id_model_type_index');

            // Foreign key constraint
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade'); // Cascade on delete
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_has_roles');
    }
}
