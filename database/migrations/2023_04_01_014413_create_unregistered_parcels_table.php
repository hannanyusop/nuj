<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnregisteredParcelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unregistered_parcels', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parcel_id')->unsigned()->nullable();
            $table->string('tracking_no');
            $table->string('receiver_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->string('order_origin')->nullable();
            $table->string('remark')->nullable();
            $table->timestamps();
        });

        Schema::table('unregistered_parcels', function (Blueprint $table) {
            $table->foreign('parcel_id')->references('id')->on('parcels')->onDelete('cascade');
        });

        Schema::create('trip_batches', function (Blueprint $blueprint){
            $blueprint->id();
            $blueprint->date('date');
            $blueprint->integer('status')->default(1);
            $blueprint->bigInteger('created_by')->unsigned();
            $blueprint->timestamps();

            $blueprint->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('trips', function (Blueprint $table) {
            $table->integer('trip_batch_id')->unsigned()->after('id');
//            $table->foreign('trip_batch_id')->references('id')->on('trip_batches')->onDelete('cascade');
            $table->dropColumn('status');
            $table->dropColumn('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unregistered_parcels');

        Schema::table('trips', function (Blueprint $table) {
            $table->integer('status')->default(1);
            $table->date('date')->nullable();
            $table->dropColumn('trip_batch_id');
        });

        Schema::dropIfExists('trip_batches');

    }
}
