<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ta_vehicles';

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = ['registration', 'user_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    // protected $hidden = ['password', 'remember_token'];

    public function owner() 
    {
        return $this->belongsTo('App\User');
    }

    public function fillups()
    {
        return $this->hasMany('App\Fillup')->orderBy('fillup_date', 'desc');
    }
}
