<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique()->nullable();
            $table->timestamps();
        });

        Schema::table('parcels', function (Blueprint $table) {
            $table->text('categories')->nullable(true);
        });
    }

    public function down()
    {

        Schema::dropIfExists('categories');

        Schema::table('parcels', function (Blueprint $table) {
            $table->dropColumn('categories');
        });
    }
}
