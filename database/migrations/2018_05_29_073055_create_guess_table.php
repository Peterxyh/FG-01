<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guess', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned();
            $table->string('game_name');
            $table->integer('team_id_one')->unsigned();
            $table->integer('team_id_two')->unsigned();
            $table->float('odds_one')->default(0);
            $table->float('odds_two')->default(0);
            $table->float('odds_draw')->default(0);
            $table->tinyInteger('result')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();

            $table->index('category_id');
            $table->index('result');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guess');
    }
}
