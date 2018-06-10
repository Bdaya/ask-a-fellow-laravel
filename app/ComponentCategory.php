<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComponentCategory extends Model
{
    protected $fillable = [
        'name'
    ];
    
    public function components()
    {
        return $this->hasMany('App\Component', 'category_id');
    }
}
