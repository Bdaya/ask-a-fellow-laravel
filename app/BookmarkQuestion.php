<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookmarkQuestion extends Model
{
	protected $table = 'bookmark_questions';
	
	 public function question()
    {
        return $this->belongsTo('App\Question');
    }

	 public function bookmarker()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}