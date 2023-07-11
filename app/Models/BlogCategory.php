<?php

namespace App\Models;

use App\Interfaces\ModelColumns;
use App\Traits\ModelHelper;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogCategory extends Model implements ModelColumns
{
    use HasFactory, Sortable;
    use ModelHelper{
        add as protected addHelper;
    }

    protected $fillable = ['status', 'image', 'video'];

    private $main_table = 'blog_categories';
    private $translate_table = 'blog_category_translates';
    private static $translates_class = 'App\Models\BlogCategoryTranslate';
    private static $current_class = __CLASS__;
    private static $sortable = true;

    public function content() :HasMany
    {
        return $this->hasMany(BlogCategoryTranslate::class, 'parent_id', 'id');
    }

    public function getItem($id, $lang)
    {
        return $this->where('id', $id)
                    ->select('id', 'status', 'slug', 'created_at')
                    ->with(
                        [
                            'content' => function($query) use($lang){
                                $query->when($lang, function ($q) use($lang){
                                    $q->where('lang', $lang);
                                });
                            }
                        ])
                    ->first();
    }

    public function getAll($lang = 'ka', $status = false)
    {
        return $this->when($status, function ($query){
                        return $query->where('status', 1);
                    })
                    ->with(['content' => function($query) use($lang){
                        $query->select('parent_id', 'title', 'meta_description', 'lang')
                            ->where('lang', $lang);
                    }])
                    ->latest()
                    ->get();
    }

    public function add($request)
    {
        $item = new self::$current_class;
        $item->sort = $this->addSort();
        
        return $this->addHelper(
            $request,
            $item
        );
    }
}
