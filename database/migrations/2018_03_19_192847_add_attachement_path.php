<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAttachementPath extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('attachement_path')->default('');
        });

        Schema::table('components_questions', function (Blueprint $table) {
            $table->string('attachement_path')->default('');
        });

        Schema::table('answers', function (Blueprint $table) {
            $table->string('attachement_path')->default('');
        });

        Schema::table('components_answers', function (Blueprint $table) {
            $table->string('attachement_path')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('questions');
        Schema::drop('answers');
        Schema::drop('components_questions');
        Schema::drop('components_answers');
    }
}
