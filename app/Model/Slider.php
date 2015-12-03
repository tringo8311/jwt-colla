<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sliders';

    protected $fillable = array('store_id', 'content', 'banner_url');

    protected $guarded = array('id');

    public function store(){
        return $this->hasOne('App\Model\Store', 'store_id');
    }

}
