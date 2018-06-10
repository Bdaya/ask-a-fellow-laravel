<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComponentsAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('components_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->text('answer');
            $table->integer('component_question_id')->unsigned()->index();
            $table->integer('responder_id')->unsigned()->index();
            $table->foreign('component_question_id')->references('id')->on('components_questions')->onDelete('cascade');
            $table->foreign('responder_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('components_answers');
    }
}
