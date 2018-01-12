<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['review', 'rate', 'user_id', 'store_id'];

    public function store()
    {
        return $this->belongsTo('App\Store', 'store_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
