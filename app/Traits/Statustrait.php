<?php

namespace App\Traits;

trait StatusTrait
{
    // check if active
    public function isActive()
    {
        return $this->status == 1;
    }
}
