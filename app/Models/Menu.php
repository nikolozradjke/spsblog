<?php

namespace App\Models;

use App\Traits\MenuHelper;
use App\Traits\ModelHelper;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class Menu extends Model
{
    use HasFactory, Sortable, MenuHelper;
    use ModelHelper{
            add as protected addHelper;
            updateItem as protected updateHelper;
        }

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
    private static $optional_column = 'text';//ველი რომელიც არსებობს თარგმანების ცხრილში მაგრამ დამოკიდებულია მენიუს კატეგორიაზე

    public function content(){
        return $this->hasMany(MenuTranslate::class, 'parent_id', 'id');
    }
    
    public function category(){
        return $this->hasOne(MenuCategory::class, 'id', 'category_id');
    }

    public function children(){
        return $this->hasMany(__CLASS__, 'parent_id', 'id');
    }

    public function files(){
        return $this->hasMany(MenuFile::class, 'parent_id', 'id');
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
                            },
                            'files' => function($query) use($lang){
                                $query->when($lang, function ($q) use($lang){
                                    $q->where('lang', $lang);
                                });
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

    public function add($request){
        $item = new self::$current_class;
        $item->sort = $this->addSort();
        
        return $this->addHelper(
            $request,
            $item,
            $this->categoryAction($request, $item)
        );
    }

    public function updateItem($request){
        return $this->updateHelper(
            $request,
            $this->categoryAction($request, $this)
        );
    }
}
