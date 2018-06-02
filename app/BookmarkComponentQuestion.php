<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookmarkComponentQuestion extends Model
{
	protected $table = 'bookmark_components_questions';
	
	 public function question()
    {
        return $this->belongsTo('App\ComponentQuestion');
    }

	 public function bookmarker()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}