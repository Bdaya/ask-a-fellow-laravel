<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnswerVote extends Model
{
    protected $table = 'answer_votes';

    public function answer()
    {
        return $this->belongsTo('App\Answer');
    }

    public function voter()
    {
        return $this->belongsTo('App\User');
    }
}
