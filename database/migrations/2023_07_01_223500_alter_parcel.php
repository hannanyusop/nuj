<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterParcel extends Migration
{

    public function up()
    {
        Schema::table('parcels', function (Blueprint $table){
            $table->integer('percent')->default(0)->after('price');
            $table->decimal('service_charge', 10, 2)->default(0)->after('percent');
        });
    }

    public function down()
    {
        Schema::table('', function (Blueprint $table){
            $table->dropColumn('percent');
            $table->dropColumn('service_charge');
        });
    }
}
