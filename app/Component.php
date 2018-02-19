<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $fillable = [
        'title', 'description', 'contact_info', 'price',
        'image_path', 'accepted'
    ];

    public function creator()
    {
        return $this->belongsTo('App\User')->first();
    }

    public function category()
    {
        return $this->belongsTo('App\ComponentCategory')->first();
    }

    public function questions()
    {
        return $this->hasMany('App\ComponentQuestion', 'component');
    }
}
