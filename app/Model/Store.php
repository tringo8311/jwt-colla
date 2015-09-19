<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Store extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stores';

    protected $primaryKey = 'bID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'slogan', 'company_name', 'city', 'state', 'zipcode', 'address', 'phone', 'fax', 'website', 'email', 'store_link', 'latitude', 'longtitude', 'overview'];

    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'user_stores', 'store_id', 'user_id')->withTimestamps();
    }

    /**
     * The offers that belong to the role.
     */
    public function offers()
    {
        return $this->hasMany('App\Model\StoreOffer');
    }
    /**
     * The offers that belong to the role.
     */
    public function feedbacks()
    {
        return $this->hasMany('App\Model\UserFeedback', 'user_id', 'bID');
    }

    /**
     *
     *
     */
    public function reservations(){
        return $this->hasMany('App\Model\UserReservationStore', 'store_id', 'bID');
    }

    /**
     *
     *
     */
    public function stamps(){
        return $this->hasMany('App\Model\UserStampStore', 'store_id', 'bID');
    }
    /**
     * @param $center_lat
     * @param $center_lng
     * @param $radius
     * @return mixed
     */
    public static function querySearch($center_lat, $center_lng, $radius){
        $query = sprintf("SELECT bID, title, slogan, zipcode, address, latitude as lat, longtitude as lng, city, state, phone, fax, email, website, store_link, (3959 * acos( cos( radians('%s') ) * cos( radians( latitude ) ) * cos( radians( longtitude ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( latitude ) ) ) ) AS distance FROM stores HAVING distance < '%s' ORDER BY distance LIMIT 0 , 20",
            $center_lat, $center_lng, $center_lat, $radius);
        $result = DB::select($query);
        return $result;
    }
}
