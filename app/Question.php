<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{

    public function asker()
    {
        return $this->belongsTo('App\User','asker_id');
    }

    public function answers()
    {
        return $this->hasMany('App\Answer');
    }

    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    public function semester()
    {
        return $this->course()->semester;
    }

    public function upvotes()
    {
        return $this->hasMany('App\QuestionVote')->where('type','=',0)->get();
    }

    public function downvotes()
    {
        return $this->hasMany('App\QuestionVote')->where('type','=',1)->get();
    }
}
