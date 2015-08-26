<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserFeedback extends Model
{
    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_feedbacks';

    protected $fillable = array('user_id', 'rate', 'service', 'employee', 'content', 'ip');

    protected $guarded = array('id');
}
