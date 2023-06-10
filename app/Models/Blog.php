<?php

namespace App\Models;

use App\Helper\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use App\Traits\ModelHelper;

class Blog extends Model
{
    use HasFactory, ModelHelper;

    protected $fillable = ['status', 'image', 'video'];

    private static $main_table = 'blogs';
    private static $translate_table = 'blog_translates';
    private static $translates_class = 'App\Models\BlogTranslate';
    private static $current_class = __CLASS__;

    public function content(){
        return $this->hasMany(BlogTranslate::class, 'parent_id', 'id');
    }

    public function getItem($id, $lang){
        return $this->where('id', $id)
                    ->select('id', 'status', 'image', 'video')
                    ->with(['content' => function($query) use($lang){
                        $query->when($lang, function ($q) use($lang){
                            $q->where('lang', $lang);
                        });
                    }])
                    ->first();
    }

    public function getAll($lang = 'ka', $status = false, $count = 50){
        return $this->when($status, function ($query){
                        return $query->where('status', 1);
                    })
                    ->with(['content' => function($query) use($lang){
                        return $query->select('parent_id', 'title', 'short_description');
                    }])
                    ->latest()
                    ->paginate($count);
    }
}
