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
}
