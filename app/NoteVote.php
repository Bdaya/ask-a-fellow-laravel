<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NoteVote extends Model
{
	protected $table = 'notes_votes';
	
	 public function note()
    {
        return $this->belongsTo('App\Note');
    }

	 public function voter()
    {
        return $this->belongsTo('App\User');
    }
}	