<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    protected $table = 'videos';

    // Relación One To Many
    public function comments(){
    	return $this->hasMany('App\Comment')->orderBy('id', 'desc');
    }

    // Relación One to One
    public function user(){
    	return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Scope a query to only include filtered by title.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByTitle( $query, $string )
    {
        return $query->where( 'title', 'LIKE', '%' . $string . '%' );
    }

    /**
     * Scope a query to order by title ascending.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByTitleAsc( $query )
    {
        return $query->orderBy( 'title', 'asc' );
    }

    /**
     * Scope a query to order by title descending.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByTitleDesc( $query )
    {
        return $query->orderBy( 'title', 'desc' );
    }

    /**
     * Scope a query to order oldest first.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByOldest( $query )
    {
        return $query->oldest();
    }

    /**
     * Scope a query to order latest first.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByLatest( $query )
    {
        return $query->latest();
    }
}
