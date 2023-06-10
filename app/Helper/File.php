<?php

namespace App\Helper;


use Illuminate\Support\Facades\Storage;

class File
{
    public function uploadFile($file, $destination, $oldFile = false)
    {
        if($oldFile){
            if(file_exists(public_path($oldFile)))
            {
                unlink(public_path($oldFile));
            }
        }

        $extension = $file->getClientOriginalExtension();
        $fileName = mt_rand(11111, 99999) . time() . '.' . $extension;

        if($path = $file->storeAs('public/'.$destination,$fileName)){
            return 'storage/' . $destination . '/' . $fileName;
        }

        return null;
    }
}
