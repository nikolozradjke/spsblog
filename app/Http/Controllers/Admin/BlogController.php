<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends BaseController
{
    public $data = [];
    private $model;
    private $main_columns;
    private $translate_columns;
    private static $main_table = 'blogs';
    private static $translate_table = 'blog_translates';

    protected $required_columns = ['image','title','description'];

    public function __construct()
    {
        parent::__construct();

        $this->model = new Blog();
        $this->main_columns = $this->model->getTableColumns(self::$main_table);
        $this->translate_columns = $this->model->getTableColumns(self::$translate_table);
    }

    public function index(Request $request){
        $this->data['message'] = 'Success';
        $this->data['items'] = $this->model->getAll($lang = 'ka', $status = false, $request->count);

        return $this->data;
    }

    public function getColumns(){
        $this->data['message'] = 'Success';
        $this->data['main_columns'] = $this->main_columns;
        $this->data['translate_columns'] = $this->translate_columns;

        return $this->data;
    }

    public function store(Request $request){
        $this->validate($request,[
            'translates.ka.title' => 'required',
            'translates.ka.description' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png',
        ]);

        $insert = $this->model->add($request);

        $this->data['message'] = 'Success';

        if(!$insert)
        {
            $this->data['message'] = 'დაფიქსირდა შეცდომა';
        }

        return $this->data;
    }

    public function show(Blog $blog){
        $this->data['message'] = 'Success';
        $this->data['item'] = $blog->getItem($blog->id, $lang = false);

        return $this->data;
    }

    public function update(Request $request, Blog $blog){
        $this->validate($request,[
            'translates.ka.title' => 'required',
            'translates.ka.description' => 'required',
            'translates.en.title' => 'required',
            'translates.en.description' => 'required',
            'image' => 'mimes:jpeg,jpg,png',
        ]);

        $update = $blog->updateItem($request);

        $this->data['message'] = 'Success';

        if(!$update)
        {
            $this->data['message'] = 'დაფიქსირდა შეცდომა';
        }

        return $this->data;
    }

    public function delete(Blog $blog){
        $this->data['message'] = 'დაფიქსირდა შეცდომა!';
        if($blog->delete()){
            $this->data['message'] = 'Success';
        }

        return $this->data;
    }
}
