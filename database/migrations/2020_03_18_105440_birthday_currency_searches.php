<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BirthdayCurrencySearches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('birthday_currency_searches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->double('rate', 15, 8);
            $table->string('currency', 3);
            $table->dateTime('birthday');
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
        Schema::dropIfExists('birthday_currency_searches');
    }
}
