<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserReservationStore extends Model
{
    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_reservation_store';

    protected $fillable = array('user_id','store_id','prefer','datetime','content','approved', 'answer', 'reminder');

    protected $guarded = array('id');

    /**
     * Return user information
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
    /**
     * Return user information
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store(){
        return $this->belongsTo('App\Model\Store', 'store_id');
    }
}
