<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->integer('votes');
        });


        DB::unprepared('
        CREATE TRIGGER UpdateNotesVotesIn AFTER INSERT ON `notes_votes` FOR EACH ROW
        BEGIN
         UPDATE notes SET votes = ((SELECT COUNT(*) FROM notes_votes WHERE note_id = NEW.note_id AND type = 0)
         - (SELECT COUNT(*) FROM notes_votes WHERE note_id = NEW.note_id AND type = 1)) WHERE id = NEW.note_id;
        END
        ');

        DB::unprepared('
        CREATE TRIGGER UpdateNotesVotesDel AFTER DELETE ON `notes_votes` FOR EACH ROW
        BEGIN
         UPDATE notes SET votes = ((SELECT COUNT(*) FROM notes_votes WHERE note_id = OLD.note_id AND type = 0)
         - (SELECT COUNT(*) FROM notes_votes WHERE note_id = OLD.note_id AND type = 1)) WHERE id = OLD.note_id;
        END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER UpdateNotesVotesIn');
        DB::unprepared('DROP TRIGGER UpdateNotesVotesDel');
    }
}
