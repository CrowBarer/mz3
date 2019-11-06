<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('match_id');
            $table->smallInteger('score_team1');
            $table->smallInteger('score_team2');
            $table->smallInteger('match_minute');
            $table->string('goal_getter');
            $table->boolean('is_penalty');
            $table->boolean('is_own_goal');
            $table->boolean('is_overtime');

            $table->index('match_id');
            $table->foreign('match_id')->references('match_number')->on('matches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goals');
    }
}
