<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuCategory;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public $data = [];
    private $model;
    private $main_columns;
    private $translate_columns;
    private static $main_table = 'menus';
    private static $translate_table = 'menu_translates';
    private static bool $gallery = false;

    public function __construct()
    {
        $this->model = new Menu();
        $this->main_columns = $this->model->getTableColumns(self::$main_table);
        if(self::$gallery){
            $this->main_columns[] = 'gallery';
        }
        $this->translate_columns = $this->model->getTableColumns(self::$translate_table);
        $this->data['message'] = 'Success';
    }

    public function index(Request $request){
        $this->data['items'] = $this->model->getAll($lang = 'ka', $status = false, $request->count);

        return response()->json($this->data, 200);
    }

    public function getColumns(){
        $this->data['message'] = 'Success';
        $this->data['main_columns'] = $this->main_columns;
        $this->data['translate_columns'] = $this->translate_columns;

        return response()->json($this->data, 200);
    }
    
    public function getCategories(){
        $this->data['message'] = 'Success';
        $this->data['items'] = MenuCategory::orderBy('title', 'ASC')->get();

        return response()->json($this->data, 200);
    }

    public function store(Request $request){
        $this->validate($request,[
            'translates.ka.title' => 'required'
        ]);

        $insert = $this->model->add($request);

        if(!$insert)
        {
            $this->data['message'] = 'დაფიქსირდა შეცდომა';
            return response()->json($this->data, 500);
        }

        return response()->json($this->data, 200);
    }

    public function show(Menu $menu){
        $this->data['item'] = $menu->getItem($menu->id, $lang = false);

        return response()->json($this->data, 200);
    }

    public function update(Request $request, Menu $menu){
        $this->validate($request,[
            'translates.ka.title' => 'required',
        ]);

        $update = $menu->updateItem($request);

        if(!$update)
        {
            $this->data['message'] = 'დაფიქსირდა შეცდომა';
            return response()->json($this->data, 500);
        }

        return response()->json($this->data, 200);
    }

    public function delete(Menu $menu){
        if(!$menu->delete()){
            $this->data['message'] = 'დაფიქსირდა შეცდომა';
        }

        return response()->json($this->data, 200);
    }
}