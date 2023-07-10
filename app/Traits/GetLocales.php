<?php

namespace App\Traits;

trait GetLocales {
    public function getLocales(){
        return array_keys(\LaravelLocalization::getSupportedLocales());
    }
}