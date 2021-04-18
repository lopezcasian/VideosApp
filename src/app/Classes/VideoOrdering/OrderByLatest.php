<?php

namespace App\Classes\VideoOrdering;

use App\Interfaces\OrderVideosInterface;

class OrderByLatest implements OrderVideosInterface
{
    /**
     * Scope a query to order by latest.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function order( $query )
    { 
        return $query->orderByLatest();
    }
}