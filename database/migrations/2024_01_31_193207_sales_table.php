<?php

use App\Services\Pickup\PickupHelperService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //create table daily_sales
        Schema::create('daily_sales', function (Blueprint $table){
            $table->id();
            $table->integer('office_id')->unsigned()->foreign('office_id')->references('id')->on('offices');
            $table->date('sales_date');
            $table->double('bank_transfer',8,2)->default(0.00);
            $table->double('cash',8,2)->default(0.00);
            $table->string('deposit_receipt')->nullable();
            $table->dateTime('deposit_received')->nullable();
            $table->double('total_sales',8,2)->default(0.00); //cash + bank transfer
            $table->double('expected_sales',8,2)->default(0.00); //sum of parcel total billing
            $table->timestamps();
        });

        Schema::table('pickups', function (Blueprint $table){
            $table->integer('daily_sale_id')->unsigned()->nullable()->foreign('daily_sale_id')->references('id')->on('daily_sales')->after('trip_id');
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
            //drop foreign key
            $table->dropColumn('daily_sale_id');
        });

        //drop table daily_sales
        Schema::dropIfExists('daily_sales');

    }
}
