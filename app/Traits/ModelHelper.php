<?php

namespace App\Traits;

use App\Helper\File;
use App\Models\MenuCategory;

trait ModelHelper
{

    public function getLocales(){
        return array_keys(\LaravelLocalization::getSupportedLocales());
    }

    public function getTableColumns($table) {
        $all_columns = $this->getConnection()->getSchemaBuilder()->getColumnListing($table);

        $not_needed_columns = [
            'id',
            'sort',
            'lang',
            'endpoint',
            'created_at',
            'updated_at'
        ];

        if(str_contains($table, 'translate')){
            $not_needed_columns[] = 'parent_id';
        }

        return array_values(array_diff($all_columns, $not_needed_columns));
    }

    public function add($request){
        $item = new self::$current_class;
        $request_keys = $request->except([
            '_token',
            'translates',
            'image',
            'sort',
            'endpoint'
        ]);

        $table_columns = $this->getTableColumns(self::$main_table);

        foreach ($request_keys as $key => $value){
            if(in_array($key, $table_columns))
            {
                $item->$key = $value;
            }
        }

        if(self::$main_table == 'menus' && $request->category_id){
            $menu_cat = MenuCategory::find($request->category_id);
            if($menu_cat && !is_null($menu_cat->front_endpoint) && !$request->model_id){
                abort(response()->json([
                    'message' => "შეავსეთ ყველა აუცილებელი ველი!"
                ], 400));
            }

            $item->endpoint = $menu_cat->front_endpoint . "?category_id=$request->model_id";
        }
        
        if(property_exists(self::$current_class, 'sortable')){
            $item->sort = $this->addSort();
        }

        if ($request->hasFile('image'))
        {
            $destination = strtolower(substr(strrchr(self::$current_class, "\\"), 1));
            $file = app(File::class)->uploadFile($request->image, $destination);

            $item->image = $file;
        }

        if($item->save()){
            if(property_exists(self::$current_class, 'translates_class')){
                $translates = $request->translates;
                $translate_columns = $this->getTableColumns(self::$translate_table);

                $storable_data = [];
                $iterator = 0;
                foreach($this->getLocales() as $lang_prefix){
                    $storable_data[$iterator] = [
                        'parent_id' => $item->id,
                        'lang' => $lang_prefix
                    ];

                    $content = isset($translates[$lang_prefix]) ? $translates[$lang_prefix] : $translates['ka'];

                    foreach($translate_columns as $column){
                        if(!isset($content[$column])){
                            $content[$column] = isset($translates['ka'][$column]) ? $translates['ka'][$column] : null;
                        }
                        $storable_data[$iterator][$column] = $content[$column];
                    }
                    $iterator += 1;
                }

                self::$translates_class::insert($storable_data);
            }
            //Todo gallery
            if(property_exists(self::$current_class, 'gallery') && $request->gallery){
                $gallery = [];
                foreach($request->gallery as $image){
                    $destination = strtolower(substr(strrchr(self::$current_class, "\\"), 1));
                    $file = app(File::class)->uploadFile($image, $destination);

                    $gallery[] = [
                        'parent_id' => $item->id,
                        'image' => $file
                    ];
                }
                self::$gallery::insert($gallery);
            }
            return true;
        }

        return false;
    }

    public function updateItem($request){
        $request_keys = $request->except([
            '_token',
            'translates',
            'image',
            'sort',
            'endpoint'
        ]);

        $table_columns = $this->getTableColumns(self::$main_table);

        foreach ($request_keys as $key => $value){
            if(in_array($key, $table_columns))
            {
                $this->$key = $value;
            }
        }

        if(self::$main_table == 'menus' && $request->category_id){
            $menu_cat = MenuCategory::find($request->category_id);
            if($menu_cat && !is_null($menu_cat->front_endpoint) && !$request->model_id){
                abort(response()->json([
                    'message' => "შეავსეთ ყველა აუცილებელი ველი!"
                ], 400));
            }

            $this->endpoint = $menu_cat->front_endpoint . "?category_id=$request->model_id";
        }

        if ($request->hasFile('image'))
        {
            $destination = strtolower(substr(strrchr(self::$current_class, "\\"), 1));
            $file = app(File::class)->uploadFile($request->image, $destination, $this->image);

            $this->image = $file;
        }

        if($this->update()){
            if(property_exists(self::$current_class, 'translates_class')){
                $translates = $request->translates;
                $supported_langs = $this->getLocales();

                foreach($supported_langs as $key => $lang){
                    if($item_content = $this->content()
                        ->where('parent_id', $this->id)
                        ->where('lang', $lang)
                        ->first()
                    )
                    {
                        foreach($translates[$lang] as $column => $content){
                            $updatable[$lang][$column] = is_null($content) ? $translates['ka'][$column] : $content;
                        }
                        $item_content->update($updatable[$lang]);
                    }
                    else{
                        $storable_data[$key] = [
                            'parent_id' => $this->id,
                            'lang' => $lang
                        ];
                        foreach($translates[$lang] as $column => $content){
                            $storable_data[$key][$column] = is_null($content) ? $translates['ka'][$column] : $content;
                        }
                        self::$translates_class::insert($storable_data);
                    }
                }
            }

            return true;
        }
        return false;
    }

}
