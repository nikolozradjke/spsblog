<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    public $data = [];
    private $model;
    private $main_columns;
    private $translate_columns;
    private static $main_table = 'blog_categories';
    private static $translate_table = 'blog_category_translates';
    private static bool $gallery = false;

    public function __construct()
    {
        $this->model = new BlogCategory();
        $this->main_columns = $this->model->getTableColumns(self::$main_table);
        if(self::$gallery){
            $this->main_columns[] = 'gallery';
        }
        $this->translate_columns = $this->model->getTableColumns(self::$translate_table);
        $this->data['message'] = 'Success';
    }

    public function index(){
        $this->data['items'] = $this->model->getAll($lang = 'ka', $status = false);

        return response()->json($this->data, 200);
    }

    public function getColumns(){
        $this->data['message'] = 'Success';
        $this->data['main_columns'] = $this->main_columns;
        $this->data['translate_columns'] = $this->translate_columns;

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

    public function show(BlogCategory $category){
        $this->data['item'] = $category->getItem($category->id, $lang = false);

        return response()->json($this->data, 200);
    }

    public function update(Request $request, BlogCategory $category){
        $this->validate($request,[
            'slug' => 'required',
            'translates.ka.title' => 'required',
        ]);

        $update = $category->updateItem($request);

        if(!$update)
        {
            $this->data['message'] = 'დაფიქსირდა შეცდომა';
            return response()->json($this->data, 500);
        }

        return response()->json($this->data, 200);
    }

    public function delete(BlogCategory $category){
        if(!$category->delete()){
            $this->data['message'] = 'დაფიქსირდა შეცდომა';
        }

        return response()->json($this->data, 200);
    }
}
