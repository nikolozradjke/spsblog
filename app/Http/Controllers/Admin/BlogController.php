<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    private $model;
    private $main_columns;
    private $translate_columns;
    private static bool $gallery = true;

    public function __construct()
    {
        $this->model = new Blog();
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

    public function store(Request $request)
    {
        $this->validate($request,[
            'translates.ka.title' => 'required',
            'translates.ka.description' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png',
        ]);

        $insert = $this->model->add($request);

        if(!$insert)
        {
            $this->error();
        }

        return $this->getResponse();
    }

    public function show(Blog $blog)
    {
        $this->data['item'] = $blog->getItem($blog->id, $lang = false);

        return $this->getResponse();
    }

    public function update(Request $request, Blog $blog)
    {
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
            $this->error();
        }

        return $this->getResponse();
    }

    public function delete(Blog $blog)
    {
        if(!$blog->delete()){
            $this->error();
        }

        return $this->getResponse();
    }

    public function deleteImage(Request $request, Blog $blog)
    {
        if(!$blog->deleteImage()){
            $this->error();
        }

        return $this->getResponse();
    }

    public function deleteGalleryImage(Request $request, Blog $blog)
    {
        $this->validate($request,[
            'image_id' => 'required'
        ]);

        if(!$blog->deleteGalleryImage($request)){
            $this->error();
        }

        return $this->getResponse();
    }
}
