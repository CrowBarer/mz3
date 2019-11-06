<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('match_number');
            $table->string('match_dateTime');
            $table->string('timezone_id');
            $table->string('league_id');
            $table->string('league_name');
            $table->integer('team1');
            $table->integer('team2');
            $table->string('last_update_dateTime');
            $table->boolean('match_is_finished')->default(false);
            $table->integer('group');

            $table->index('match_number');
            $table->index('team1');
            $table->index('team2');
            $table->index('group');

            $table->foreign('team1')->references('team_id')->on('teams');
            $table->foreign('team2')->references('team_id')->on('teams');
            $table->foreign('group')->references('group_id')->on('groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matches');
    }
}
