<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuCategory;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    private $model;
    private $main_columns;
    private $translate_columns;
    private static bool $gallery = false;

    public function __construct()
    {
        $this->model = new Menu();
        $this->main_columns = $this->model->getMainColumns();
        if(self::$gallery){
            $this->main_columns[] = 'gallery';
        }
        $this->translate_columns = $this->model->getTranslateColumns();
        $this->data['message'] = 'Success';
    }

    public function index(Request $request)
    {
        $this->data['items'] = $this->model->getAll($lang = 'ka', $status = false, $request->count);

        return $this->getResponse();
    }

    public function getColumns()
    {
        $this->data['main_columns'] = $this->main_columns;
        $this->data['translate_columns'] = $this->translate_columns;

        return $this->getResponse();
    }
    
    public function getCategories()
    {
        $this->data['message'] = 'Success';
        $this->data['items'] = MenuCategory::orderBy('title', 'ASC')->get();

        return $this->getResponse();
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'translates.ka.title' => 'required'
        ]);

        $insert = $this->model->add($request);

        if(!$insert)
        {
            $this->error();
        }

        return $this->getResponse();
    }

    public function show(Menu $menu)
    {
        $this->data['item'] = $menu->getItem($menu->id, $lang = false);

        return $this->getResponse();
    }

    public function update(Request $request, Menu $menu)
    {
        $this->validate($request,[
            'translates.ka.title' => 'required',
        ]);

        $update = $menu->updateItem($request);

        if(!$update)
        {
            $this->error();
        }

        return $this->getResponse();
    }

    public function sort(Request $request)
    {
        if(!$this->model->sort($request)){
            $this->error();
        }

        return $this->getResponse();
    }

    public function delete(Menu $menu)
    {
        if(!$menu->delete()){
            $this->error();
        }

        return $this->getResponse();
    }
}