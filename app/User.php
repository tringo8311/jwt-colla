<?php
namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'email', 'password', 'role',  'code', 'first_name', 'last_name', 'mobile', 'address', 'zipcode'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Get the notes for the blog post.
     */
    public function notes()
    {
        return $this->hasMany('App\Model\UserNote', 'user_id', 'id');
    }
    /**
     * Get the feedbacks for the blog post.
     */
    public function feedbacks()
    {
        return $this->hasMany('App\Model\UserFeedback', 'user_id', 'id');
    }

    /**
     * The roles that belong to the user.
     */
    public function stores()
    {
        return $this->belongsToMany('App\Model\Store', 'user_stores', 'user_id', 'store_id')->withTimestamps();
    }
}

class UserObserver {

    public function saving($activity)
    {
        $activity->code = hexdec(uniqid());
    }
}