<?php

namespace App\Traits;

trait StatusTrait
{
    public function isActive()
    {
        return $this->status == 1;
    }
}
