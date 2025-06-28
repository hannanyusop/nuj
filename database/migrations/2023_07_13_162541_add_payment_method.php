<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentMethod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offices', function (Blueprint $table){
            $table->longText('whatsapp_template')->nullable()->after('operation_day');
            $table->longText('pickup_remark')->nullable()->after('whatsapp_template');
        });
        Schema::table('pickups', function (Blueprint $table){
            $table->integer('payment_method')->nullable()->after('status');
            $table->integer('payment_status')->nullable()->after('payment_method')->default(1);
            $table->float('total_payment')->nullable()->default(0.00)->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offices', function (Blueprint $table){
            $table->dropColumn('whatsapp_template');
            $table->dropColumn('pickup_remark');
        });

        Schema::table('pickups', function (Blueprint $table){
            $table->dropColumn('payment_method');
            $table->dropColumn('payment_status');
            $table->dropColumn('total_payment');
        });
    }
}
