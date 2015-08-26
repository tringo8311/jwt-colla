<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StoreOffer extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'store_offers';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['subject', 'off', 'off_max', 'off_type', 'file_url', 'content', 'start_time', 'end_time', 'user_id', 'store_id'];
}
