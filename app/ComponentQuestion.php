<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComponentQuestion extends Model
{
    protected $fillable = [
        'question'
    ];
    protected $table = "components_questions";

    public function asker()
    {
        return $this->belongsTo('App\User')->first();
    }

    public function component()
    {
        return $this->belongsTo('App\Component')->first();
    }

    public function answers()
    {
        return $this->hasMany('App\ComponentAnswer');
    }
}
