<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [

        'title', 'description', 'date', 'place', 'course_id'
    ];

    public function calenders()
    {
        return $this->belongsToMany('App\Calender','calenders_events');
    }

    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    public function announcements()
    {
        return $this->hasMany('App\Announcement');
    }

    public function creator()
    {
        return $this->belongsTo('App\User');
    }

}
