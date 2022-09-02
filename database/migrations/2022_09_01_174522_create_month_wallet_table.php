<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthWalletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('month_wallet', function (Blueprint $table) {
            $table->unsignedBigInteger('wallet_id');
            $table->unsignedBigInteger('month_id');
            $table->integer('amount')->default(0);
            $table->timestamps();

            $table->unique(['wallet_id', 'month_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('month_wallet');
    }
}
