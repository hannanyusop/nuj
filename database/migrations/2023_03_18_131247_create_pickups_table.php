<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Services\Pickup\PickupHelperService;

class CreatePickupsTable extends Migration
{
    public function up()
    {
        Schema::create('pickups', function (Blueprint $table) {
            $table->id();
            $table->integer('trip_id')->unsigned();
            $table->integer('office_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->integer('status')->default(PickupHelperService::STATUS_PENDING)->nullable();
            $table->integer('serve_by')->unsigned()->nullable();
            $table->string('code')->unique();
            $table->string('pickup_name')->nullable();
            $table->string('prof_of_delivery')->nullable();
            $table->dateTime('pickup_datetime')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('parcels', function (Blueprint $table){
//            $table->integer('pickup_id')->unsigned()->after('user_id');

            $table->dropColumn('trip_id');
            $table->dropColumn('pickup_datetime');
            $table->dropColumn('pickup_name');
            $table->dropColumn('serve_by');
        });
    }


    public function down()
    {
        Schema::dropIfExists('pickups');


        Schema::table('parcels', function (Blueprint $table){
            $table->integer('trip_id')->unsigned()->after('id')->nullable();
            $table->string('pickup_name')->after('phone_number');
            $table->string('serve_by')->after('pickup_id')->nullable();
            $table->dateTime('pickup_datetime')->after('serve_by')->nullable();
            $table->dropColumn('pickup_id');
        });
    }
}
