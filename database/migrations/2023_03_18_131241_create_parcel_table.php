<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParcelTable extends Migration
{
    public function up()
    {
        Schema::create('parcels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trip_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('office_id')->nullable();
            $table->integer('status')->default(0);
            $table->string('tracking_no', 100)->unique();
            $table->string('receiver_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('pickup_name')->nullable();
            $table->string('pickup_info')->nullable();
            $table->bigInteger('pickup_id')->nullable();
            $table->bigInteger('serve_by')->nullable();
            $table->datetime('pickup_datetime')->nullable();
            $table->string('order_origin')->nullable();
            $table->string('description')->nullable();
            $table->integer('quantity')->nullable();
            $table->bigInteger('price')->nullable();
            $table->bigInteger('tax')->nullable();
            $table->string('invoice_url')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('parcels');
    }
}
