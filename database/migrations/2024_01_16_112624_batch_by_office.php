<?php

use App\Domains\Auth\Models\Office;
use App\Models\TripBatch;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BatchByOffice extends Migration
{
    public function up()
    {
        Schema::table('trip_batches', function (Blueprint $table) {

            //fk office_id
            $table->unsignedBigInteger('office_id');
            $table->foreign('office_id')
                ->references('id')
                ->on('offices');

            $table->boolean('is_closed')->default(false);

            $table->index('office_id');
        });

        Schema::table('offices', function (Blueprint $table) {
            $table->boolean('is_receiver')->nullable()->after('is_drop_point')->default(false);
        });

        TripBatch::where('is_closed', false)->update([
            'is_closed' => true,
            'office_id' => Office::where('code', 'LMN')->first()?->id
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trip_batches', function (Blueprint $table) {
            $table->dropForeign(['office_id']);
            $table->dropColumn('office_id');
            $table->dropColumn('is_closed');
        });

        Schema::table('offices', function (Blueprint $table) {
            $table->dropColumn('is_receiver');
        });
    }
}
