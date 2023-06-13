<?php

namespace App\Http\Controllers\Client;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public $data = [];
    private $model;

    public function __construct(){
        $this->model = new Blog();
        $this->data['message'] = 'Success';
    }

    public function index(Request $request){
        $this->data['items'] = $this->model->getAll(
            $lang = $request->lang,
            $status = true,
            $category = $request->category_id,
            $count = $request->count);

        return response()->json($this->data, 200);  
    }
}
