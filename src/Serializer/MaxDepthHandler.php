<?php

namespace App\Serializer;

class MaxDepthHandler
{
    public function __invoke($innerObj)
    {
        return null;
    }
}