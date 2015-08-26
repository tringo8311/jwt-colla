<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserStore extends Model
{
    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_stores';

    protected $primaryKey = ['store_id', 'user_id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'store_id'];

}
