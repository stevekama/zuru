<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('sequence_one');
            $table->string('sequence_two');
            $table->string('sequence_three');
            $table->string('sequence_four');
            $table->unsignedBigInteger('user_id');
            $table->float('balance')->default(0);
            $table->integer('status')->default(0);
            $table->date('expiry');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
