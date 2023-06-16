<?php

namespace App\Models;

use App\Helper\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelHelper;

class Blog extends Model
{
    use HasFactory, ModelHelper;

    protected $fillable = [
        'slider',
        'slider_to',
        'main_page',
        'status',
        'image',
        'video',
        'category_id'
    ];

    private static $main_table = 'blogs';
    private static $translate_table = 'blog_translates';
    private static $translates_class = 'App\Models\BlogTranslate';
    private static $current_class = __CLASS__;
    private static $gallery = 'App\Models\BlogImage';
    private static $date_columns = [
        'slider_to' => 'Y-m-d'
    ];

    public function content(){
        return $this->hasMany(BlogTranslate::class, 'parent_id', 'id');
    }

    public function gallery(){
        return $this->hasMany(BlogImage::class, 'parent_id', 'id');
    }

    public function getItem($id, $lang){
        return $this->where('id', $id)
                    ->select('id', 'status', 'image', 'video', 'created_at')
                    ->with(
                        [
                            'content' => function($query) use($lang){
                                $query->when($lang, function ($q) use($lang){
                                    $q->where('lang', $lang);
                                });
                            },
                            'gallery' => function($query){
                                $query->select('id', 'image', 'parent_id');
                            }
                        ])
                    ->first();
    }

    public function getAll($lang = 'ka', $status = false, $category = false, $count = 50){
        return $this->when($status, function ($query){
                        return $query->where('status', 1);
                    })
                    ->when($category, function ($query) use($category){
                        return $query->where('category_id', $category);
                    })
                    ->with(['content' => function($query) use($lang){
                        $query->select('parent_id', 'title', 'short_description', 'lang')
                            ->where('lang', $lang);
                    }])
                    ->latest()
                    ->paginate($count);
    }

    public function deleteImage(){
        $file_remove = app(File::class)->removeFile($this->image);
        $this->image = null;

        if($file_remove){
            return $this->save();
        }

        return false;
    }

    public function deleteGalleryImage($request){
        $gallery = self::$gallery::where('id', $request->image_id)->where('parent_id', $this->id)->first();
        if($gallery && app(File::class)->removeFile($gallery->image)){
            return $gallery->delete();
        }

        return false;
    }
}
