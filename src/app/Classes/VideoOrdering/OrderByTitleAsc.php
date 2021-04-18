<?php

namespace App\Classes\VideoOrdering;

use App\Interfaces\OrderVideosInterface;

class OrderByTitleAsc implements OrderVideosInterface
{
    /**
     * Scope a query to order by title ascending.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function order( $query )
    { 
        return $query->orderByTitleAsc();
    }
}