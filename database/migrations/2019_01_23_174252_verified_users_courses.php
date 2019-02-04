<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VerifiedUsersCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verified_users_courses', function (Blueprint $table) {
            $table->timestamps();
            $table->integer('course_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->primary(['course_id', 'user_id']);	 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('verified_users_courses'); 
    }
}
