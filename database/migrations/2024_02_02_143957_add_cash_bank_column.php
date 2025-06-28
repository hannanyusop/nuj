<?php

use App\Models\Pickup;
use App\Services\Pickup\PickupHelperService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCashBankColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pickups', function (Blueprint $table) {
            $table->double('cash_received', 8,2)->default(0.00)->after('payment_status');
            $table->double('bank_transfer_received', 8,2)->default(0.00)->after('cash_received');

            $table->string('notes')->nullable()->after('notification_sent');
        });

        $pickups = Pickup::all();

        foreach ($pickups as $pickup) {
            $pickup->cash_received          = $pickup->payment_method == PickupHelperService::PAYMENT_METHOD_CASH          ? $pickup->total_payment : 0.00;
            $pickup->bank_transfer_received = $pickup->payment_method == PickupHelperService::PAYMENT_METHOD_BANK_TRANSFER ? $pickup->total_payment : 0.00;
            $pickup->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('pickups', function (Blueprint $table) {
            $table->dropColumn('cash_received');
            $table->dropColumn('bank_transfer_received');
        });
    }
}
