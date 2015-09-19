<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserStampStore extends Model
{
    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_stamp_store';

    protected $fillable = array('created_id', 'user_id', 'store_id', 'datetime', 'content', 'used', 'paid');

    protected $guarded = array('id');
}
