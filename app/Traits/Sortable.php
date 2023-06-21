<?php

namespace App\Traits;

trait Sortable
{
    public function addSort(){
        $sort = self::max('sort');

        return $sort ? $sort + 1 : 1;
    }

    public function sort($request){
        foreach($request->data as $item){
            $sortable = self::$current_class::where('id', $item['id'])->first();
            if($sortable){
                $sortable->update($item);
            }
        }

        return true;
    }
}
