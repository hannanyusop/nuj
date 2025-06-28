<?php

use App\Services\Pickup\PickupHelperService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ImplmentNotificationEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pickups', function (Blueprint $table){
            $table->dateTime('notification_send_at')->nullable()->after('pickup_datetime');
            $table->boolean('notification_sent')->default(false)->after('notification_send_at');
        });

        DB::table('pickups')
            ->where('status', PickupHelperService::STATUS_DELIVERED)
            ->update([
            'notification_sent' => 1,
            'notification_send_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pickups', function (Blueprint $table){
            $table->dropColumn('notification_send_at');
            $table->dropColumn('notification_sent');
        });
    }
}
