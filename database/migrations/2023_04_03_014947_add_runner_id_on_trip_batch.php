<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Services\Trip\TripHelperService;
use App\Services\TripBatch\TripBatchHelperService;

class AddRunnerIdOnTripBatch extends Migration
{

    public function up()
    {
        Schema::table('trip_batches', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('trips', function (Blueprint $table) {
            $table->integer('status')->default(TripHelperService::STATUS_PENDING)->after('id');
        });
    }

    public function down()
    {
        Schema::table('trip_batches', function (Blueprint $table) {
            $table->integer('status')->default(TripBatchHelperService::STATUS_PENDING)->after('id');
        });

        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
