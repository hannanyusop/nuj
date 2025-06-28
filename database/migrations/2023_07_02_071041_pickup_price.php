<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PickupPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pickups', function (Blueprint $table){
            $table->integer('total_price')->after('pickup_name')->default(0.00);
        });

        Schema::table('parcels', function (Blueprint $table){
            $table->integer('code')->after('tracking_no')->nullable();
            $table->float('cod_fee')->after('price')->default(0.00);
            $table->string('guni')->after('order_origin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pickups', function (Blueprint $table){
            $table->dropColumn('total_price');
        });

        Schema::table('parcels', function (Blueprint $table){
            $table->dropColumn('code');
            $table->dropColumn('cod_fee');
            $table->dropColumn('guni');
        });
    }
}
