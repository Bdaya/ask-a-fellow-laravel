<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VerifiedUsersCourses extends Model
{
    //
    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function courses()
    {
        return $this->hasMany('App\Course');
    }
}
