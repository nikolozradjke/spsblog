<?php

namespace App\Traits;

use App\Helper\File;
use App\Models\MenuCategory;

trait MenuHelper
{
    use GetLocales;

    private $request;
    private $menu_cat;

    public function categoryAction($request, $item){
        $this->request = $request;
        $this->menu_cat = MenuCategory::find($this->request->category_id);
        if(!$this->menu_cat) return true;
        switch ($this->menu_cat->title) {
            case 'სიახლეები':
                return $item->endpoint = $this->blogCategory($this->menu_cat);
            case 'მთავარი':
                return null;
            case 'კონტაქტი':
                return null;
            case 'ტექსტური გვერდი':
                return $this->blogTextField();        
        }
    }

    public function blogCategory(){
        $column = $this->menu_cat->column;
        if($this->menu_cat->front_endpoint && !$this->request->model_id){
            abort(response()->json([
                'message' => "შეავსეთ ყველა აუცილებელი ველი! $column"
            ], 400));
        }
        
        return $this->menu_cat->front_endpoint . "?category_id=" . $this->request->model_id;
    }

    public function blogTextField(){
        $column = $this->menu_cat->column;
        if(!$this->request->$column){
            abort(response()->json([
                'message' => "შეავსეთ ყველა აუცილებელი ველი! $column"
            ], 400));
        }
        $response = [];
        foreach($this->getLocales() as $lang){
            if(isset($this->request->$column[$lang])){
                $response[$this->menu_cat->column][$lang] = $this->request->$column[$lang];
            }else{
                $response[$this->menu_cat->column][$lang] = $this->request->$column['ka'];
            }
        }

        return $response;
    }
}
