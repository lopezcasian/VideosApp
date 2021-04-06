<?php

namespace App\Classes;

use App\Interfaces\OrderVideosInterface;
use App\Video;

class OrderVideosTitleAsc implements OrderVideosInterface
{
    public function __construct( Video $video )
    {
        $this->video = $video;
    }

    /**
     * Scope a query to order by title ascending.
     *
     * @return \App\Video
     */
    public function getOrderScope()
    { 
        return $this->video->orderByTitleAsc();
    }
}