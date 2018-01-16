<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'name', 'location', 'rate_count', 'logo', 'description', 'phone'
    ];

    public function reviews()
    {
        return $this->hasMany('App\Review');
    }
}
