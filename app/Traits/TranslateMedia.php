<?php

namespace App\Traits;

use App\Helper\File;

trait TranslateMedia {
    use GetLocales;

    protected array $translate_media_files = [];

    public function addMedia($request, $item){
        $destination = strtolower(substr(strrchr(self::$current_class, "\\"), 1));
        foreach($this->getLocales() as $lang){
            foreach($this->translate_media as $column_name){
                if(isset($request->translates[$lang][$column_name])){
                    $file = app(File::class)->uploadFile($request->translates[$lang][$column_name], $destination);
                    $this->translate_media_files[$column_name][$lang] = $file;
                }else{
                    $old = $item->content()->where('lang', $lang)->first()->$column_name;
                    $this->translate_media_files[$column_name][$lang] = $old;
                }
            }
        }

        return $this->translate_media_files;
    }
}