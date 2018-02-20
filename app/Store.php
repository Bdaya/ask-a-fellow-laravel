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

    /**
     * Adds a new rating to the store
     * @param rating : a rating value from 1 to 10
     */
    public function add_rating($rating)
    {
        $old_rate = $this->rate;
        $count = $this->rate_count;
        $new_rate = $old_rate * ($count/($count+1)) + ($rating/($count+1));

        $this->rate = $new_rate;
        $this->rate_count = $count+1;
        $this->save();
    }

    /**
     * Updates an old rating to the store
     * @param old_rating : the previous rating which takes a value from 1 to 10
     * @param new_rating : the new rating which takes a value from 1 to 10
     */
    public function alter_rating($old_rating, $new_rating)
    {
        $rate = $this->rate;
        $count = $this->rate_count;
        $rate = $rate - ($old_rating/$count) + ($new_rating/$count);

        $this->rate = $rate;
        $this->save();
    }
}
