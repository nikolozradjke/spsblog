<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    private $model;
    private $main_columns;
    private $translate_columns;
    private static bool $gallery = false;

    public function __construct()
    {
        $this->model = new BlogCategory();
        $this->main_columns = $this->model->getMainColumns();
        if(self::$gallery){
            $this->main_columns[] = 'gallery';
        }
        $this->translate_columns = $this->model->getTranslateColumns();
        $this->data['message'] = 'Success';
    }

    public function index()
    {
        $this->data['items'] = $this->model->getAll($lang = 'ka', $status = false);

        return $this->getResponse();
    }

    public function getColumns()
    {
        $this->data['main_columns'] = $this->main_columns;
        $this->data['translate_columns'] = $this->translate_columns;

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

    public function show(BlogCategory $category)
    {
        $this->data['item'] = $category->getItem($category->id, $lang = false);

        return $this->getResponse();
    }

    public function update(Request $request, BlogCategory $category)
    {
        $this->validate($request,[
            'slug' => 'required',
            'translates.ka.title' => 'required',
        ]);

        $update = $category->updateItem($request);

        if(!$update)
        {
            $this->error();
        }

        return $this->getResponse();
    }

    public function delete(BlogCategory $category)
    {
        if(!$category->delete()){
            $this->error();
        }

        return $this->getResponse();
    }
}
