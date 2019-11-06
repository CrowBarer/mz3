<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('match_id');
            $table->string('result_name');
            $table->integer('points_team_1');
            $table->integer('points_team_2');
            $table->integer('result_order');
            $table->integer('result_type');

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
        Schema::dropIfExists('match_results');
    }
}
