<?php
namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\DB;

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
    protected $fillable = ['username', 'email', 'password', 'role',  'code', 'first_name', 'last_name', 'mobile', 'address', 'zipcode', 'activated'];

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

    /**
     *
     */
    public function reservations(){
        return $this->hasMany('App\Model\UserReservationStore', 'user_id', 'id');
    }

    /**
     *
     */
    public function stamps(){
        return $this->hasMany('App\Model\UserStampStore', 'user_id', 'id');
    }

    /**
     * @param $currentUserId
     * @param $storeId
     * @param $keyword
     */
    public static function querySearch($storeId, $currentUserId, $keyword){
        DB::enableQueryLog();
        $query = 'SELECT u.* FROM users AS u JOIN user_stores AS us ON u.id = us.user_id JOIN stores AS s ON s.bID = us.store_id';
        $query .= ' WHERE s.bID = ?';
        if(!empty($keyword)){
            $query .= ' AND (u.first_name LIKE "%' . $keyword . '%" OR u.last_name LIKE "%'
                . $keyword . '%" OR u.mobile LIKE "%' . $keyword . '%" OR u.code LIKE "%' . $keyword . '%")';
        }
        $query .= ' HAVING u.id != ?';
        $result = DB::select($query, array($storeId,  $currentUserId));
        //$result = DB::select($query, array($storeId, $keyword, $keyword, $currentUserId));
        /*$queries = DB::getQueryLog();
        $last_query = end($queries);
        var_dump($last_query);*/
        return $result;

    }
}

use Jenssegers\Optimus\Optimus;
class UserObserver {

    /**
     * Use for case create user
     * @param $activity
     *
     */
    public function saving($activity){
        $optimus = new Optimus(1580030173, 59260789, 1163945558);
        $encoded = $optimus->encode(hexdec(uniqid()));
        $activity->code = $encoded;
    }
}