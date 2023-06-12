<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public $data = [];
    private $model;
    private $main_columns;
    private $translate_columns;
    private static $main_table = 'blogs';
    private static $translate_table = 'blog_translates';
    private static bool $gallery = true;

    protected $required_columns = ['image','title','description'];

    public function __construct()
    {
        $this->model = new Blog();
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

    public function store(Request $request){
        $this->validate($request,[
            'translates.ka.title' => 'required',
            'translates.ka.description' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png',
        ]);

        $insert = $this->model->add($request);

        if(!$insert)
        {
            $this->data['message'] = 'დაფიქსირდა შეცდომა';
            return response()->json($this->data, 500);
        }

        return response()->json($this->data, 200);
    }

    public function show(Blog $blog){
        $this->data['item'] = $blog->getItem($blog->id, $lang = false);

        return response()->json($this->data, 200);
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

        if(!$update)
        {
            $this->data['message'] = 'დაფიქსირდა შეცდომა';
            return response()->json($this->data, 500);
        }

        return response()->json($this->data, 200);
    }

    public function delete(Blog $blog){
        if(!$blog->delete()){
            $this->data['message'] = 'დაფიქსირდა შეცდომა';
        }

        return response()->json($this->data, 200);
    }

    public function deleteImage(Request $request, Blog $blog){
        if(!$blog->deleteImage()){
            $this->data['message'] = 'დაფიქსირდა შეცდომა';
            return response()->json($this->data, 500);
        }

        return response()->json($this->data, 200);
    }

    public function deleteGalleryImage(Request $request, Blog $blog){
        $this->validate($request,[
            'image_id' => 'required'
        ]);

        if(!$blog->deleteGalleryImage($request)){
            $this->data['message'] = 'დაფიქსირდა შეცდომა';
            return response()->json($this->data, 500);
        }

        return response()->json($this->data, 200);
    }
}
