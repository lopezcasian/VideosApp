<?php

namespace App\Classes;

use App\Interfaces\OrderVideosInterface;
use App\Video;

class OrderVideosLatest implements OrderVideosInterface
{
    public function __construct( Video $video )
    {
        $this->video = $video;
    }

    public function getOrderScope()
    { 
        return $this->video->orderByLatest();
    }
}