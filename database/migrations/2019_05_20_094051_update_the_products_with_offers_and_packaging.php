<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTheProductsWithOffersAndPackaging extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('vendor_items',function (Blueprint $table){
            $table->float('packaging_cost')->default(0);
            $table->float('previous_price')->default(0);
            $table->boolean('is_on_offer')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
