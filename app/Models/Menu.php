<?php

namespace App\Models;

use App\Traits\ModelHelper;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Menu extends Model
{
    use HasFactory, ModelHelper, Sortable;

    protected $fillable = [
        'status',
        'sort',
        'status',
        'category_id',
        'parent_id',
        'endpoint'
    ];

    private static $main_table = 'menus';
    private static $translate_table = 'menu_translates';
    private static $translates_class = 'App\Models\MenuTranslate';
    private static $current_class = __CLASS__;
    private static $sortable = true;

    public function content(){
        return $this->hasMany(MenuTranslate::class, 'parent_id', 'id');
    }
    
    public function category(){
        return $this->hasOne(MenuCategory::class, 'id', 'category_id');
    }

    public function children(){
        return $this->hasMany(__CLASS__, 'parent_id', 'id');
    }

    public function getItem($id, $lang){
        return $this->where('id', $id)
                    ->select('id', 'status', 'created_at')
                    ->with(
                        [
                            'content' => function($query) use($lang){
                                $query->when($lang, function ($q) use($lang){
                                    $q->where('lang', $lang);
                                });
                            },
                            'category',
                            'children' => function($query){
                                $query->select('id', 'status', 'created_at', 'parent_id');
                            }
                        ])
                    ->first();
    }

    public function getAll($lang = 'ka', $status = false){
        return $this->when($status, function ($query){
                        return $query->where('status', 1);
                    })
                    ->with(['content' => function($query) use($lang){
                        $query->select('parent_id', 'title', 'lang')
                            ->where('lang', $lang);
                    }])
                    ->orderBy('sort', 'ASC')
                    ->get();
    }
}
