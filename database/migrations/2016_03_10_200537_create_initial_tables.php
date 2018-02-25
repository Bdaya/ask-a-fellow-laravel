<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInitialTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at');
        });

        Schema::create('majors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('major')->unique();
            $table->string('faculty');
            $table->timestamps();
        });


        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('course_code')->unique();
            $table->string('course_name')->unique();
            $table->integer('semester')->unsigned();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('profile_picture');
            $table->integer('role');
            $table->integer('contribution');
            $table->integer('semester');
            $table->integer('major_id')->unsigned()->nullable();
            $table->text('bio');
            $table->date('birth_date');
            $table->foreign('major_id')->references('id')->on('majors')->onDelete('SET NULL');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('course_major', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('major_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->timestamps();
            $table->foreign('major_id')->references('id')->on('majors')->onDelete('CASCADE');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('CASCADE');
            $table->unique(array('major_id', 'course_id'));
        });
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->text('question');
            $table->integer('asker_id')->unsigned();
            $table->integer('course_id')->unsigned()->index();
            $table->timestamps();
            $table->foreign('asker_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('CASCADE')->onUpdate('cascade');

        });
        Schema::create('answers', function (Blueprint $table) {
            $table->increments('id');
            $table->text('answer');
            $table->integer('question_id')->unsigned();
            $table->integer('responder_id')->unsigned();
            $table->timestamps();
            $table->foreign('responder_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('CASCADE');

        });
        Schema::create('question_votes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('type')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('question_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('CASCADE');
            $table->unique(array('user_id', 'question_id'));
        });
        Schema::create('answer_votes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('type')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('answer_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('answer_id')->references('id')->on('answers')->onDelete('CASCADE');
            $table->unique(array('user_id', 'answer_id'));
        });
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('user_id')->unsigned();
            $table->text('notification_description');
            $table->text('notification_link');
            $table->boolean('seen');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('question_bookmarks', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('user_id')->unsigned();
            $table->integer('question_id')->unsigned();
            $table->boolean('notify'); //to be notified on new answers or not
            $table->text('question_link'); //could be not needed
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('CASCADE');
            $table->unique(array('user_id', 'question_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('password_resets');
        Schema::drop('majors');
        Schema::drop('courses');
        Schema::drop('course_major');
        Schema::drop('users');
        Schema::drop('questions');
        Schema::drop('answers');
        Schema::drop('question_votes');
        Schema::drop('answer_votes');
        Schema::drop('notifications');
        Schema::drop('question_bookmarks');

    }
}
