<?php

namespace App\Classes;

use App\Interfaces\OrderVideosInterface;
use App\Video;

class OrderVideosTitleDesc implements OrderVideosInterface
{
    public function __construct( Video $video )
    {
        $this->video = $video;
    }

    /**
     * Scope a query to order by title descending.
     *
     * @return \App\Video
     */
    public function getOrderScope()
    { 
        return $this->video->orderByTitleDesc();
    }
}