<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    
    public function comments()
    {
        return $this->hasMany('App\NoteComment');
    }

    public function upvotes()
    {
        return $this->hasMany('App\NoteVote')->where('type','=',0)->get();
    }

    public function downvotes()
    {
        return $this->hasMany('App\NoteVote')->where('type','=',1)->get();
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function course()
    {
        return $this->belongsTo('App\Course');
    }

}
