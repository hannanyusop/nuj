<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parcels', function (Blueprint $table){
            $table->decimal('price', 10,4)->default(0.0000)->change();
            $table->decimal('permit', 10, 4)->after('tax')->default(0.0000);
            $table->decimal('tax', 10,4)->change();
            $table->boolean('checked')->after('permit')->default(0);
        });

        Schema::table('trip_batches', function (Blueprint $table){
            $table->decimal('tax_rate', 10,4)->after('date')->default(0.3017);
            $table->decimal('pos_rate', 10, 4)->after('tax_rate')->default(2.8000);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parcels', function (Blueprint $table){
            $table->dropColumn('permit');
            $table->dropColumn('checked');
        });

        Schema::table('trip_batches', function (Blueprint $table){
            $table->dropColumn('tax_rate');
            $table->dropColumn('pos_rate');
        });
    }
}
