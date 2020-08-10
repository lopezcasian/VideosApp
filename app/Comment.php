<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;


	protected $table = 'comments';
    // Relación One to One
    public function user(){
    	return $this->belongsTo('App\User', 'user_id');
    }

    public function video(){
    	return $this->belongsTo('App\Video', 'video_id');
    }
}
