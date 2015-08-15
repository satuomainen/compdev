<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fillup extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ta_fillups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['vehicle_id', 'fillup_date', 'litres', 'amount_paid', 'mileage'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    // protected $hidden = ['password', 'remember_token'];

    public function vehicle()
    {
        return $this->belongsTo('App\Vehicle');
    }
}
