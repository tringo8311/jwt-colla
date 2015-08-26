<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserNote extends Model
{
    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_notes';

    protected $fillable = array('user_id', 'barcode', 'product_code', 'content');

    protected $guarded = array('id');
}
