<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserJoinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_join', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('guess_id')->unsigned();
            $table->integer('teams_id')->unsigned();
            $table->float('odds')->default(0);
            $table->float('amount')->default(0);
            $table->float('result')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();

            $table->index('user_id');
            $table->index('guess_id');
            $table->index('teams_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_join');
    }
}
