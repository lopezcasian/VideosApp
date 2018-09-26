<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	protected $table = 'comments';
    // Relación One to One
    public function user(){
    	return $this->belongsTo('App\User', 'Videos_Users_id');
    }
}
