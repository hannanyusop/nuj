<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id(); // Auto-incrementing BIGINT primary key
            $table->enum('type', ['admin', 'user']); // Enum type for 'admin' and 'user'
            $table->string('guard_name', 255); // Guard name, VARCHAR(255)
            $table->string('name', 255); // Name, VARCHAR(255)
            $table->string('description', 255)->nullable(); // Description, VARCHAR(255), nullable
            $table->unsignedBigInteger('parent_id')->nullable(); // Parent ID, unsigned BIGINT, nullable
            $table->tinyInteger('sort')->default(1); // Sort, TINYINT, default value 1
            $table->timestamps(); // created_at and updated_at timestamps, nullable

            // Index for parent_id
            $table->index('parent_id', 'permissions_parent_id_foreign');

            // Foreign key constraint on parent_id
            $table->foreign('parent_id')
                ->references('id')
                ->on('permissions')
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
        Schema::dropIfExists('permissions');
    }
}
