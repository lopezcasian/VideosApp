<?php

namespace App\Classes\VideoOrdering;

use App\Interfaces\OrderVideosInterface;

class OrderByTitleDesc implements OrderVideosInterface
{
    /**
     * Scope a query to order by title descending.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function order( $query )
    { 
        return $query->orderByTitleDesc();
    }
}