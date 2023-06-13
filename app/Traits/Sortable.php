<?php

namespace App\Traits;

trait Sortable
{
    public function addSort(){
        $sort = self::max('sort');

        return $sort ? $sort + 1 : 1;
    }
}
