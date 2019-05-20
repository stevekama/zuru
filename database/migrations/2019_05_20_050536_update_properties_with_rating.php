<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePropertiesWithRating extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('vendors',function (Blueprint $table){
           $table->float('rating')->default(5);
        });

        Schema::table('riders',function (Blueprint $table){
            $table->float('rating')->default(5);
        });

        Schema::table('customers',function (Blueprint $table){
            $table->float('rating')->default(5);
        });

        Schema::table('vendor_items',function (Blueprint $table){
            $table->float('rating')->default(5);
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
