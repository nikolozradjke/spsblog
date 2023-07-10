<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactInfoController extends Controller
{
    private $model;
    private $main_columns;
    private $translate_columns;
    private static bool $gallery = false;

    public function __construct()
    {
        $this->model = new Contact();
        $this->main_columns = $this->model->getMainColumns();
        if(self::$gallery){
            $this->main_columns[] = 'gallery';
        }
        $this->translate_columns = $this->model->getTranslateColumns();
        $this->data['message'] = 'Success';
    }

    public function index()
    {
        $this->data['items'] = $this->model->getItem();

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

    public function update(Request $request, Contact $contact)
    {
        // $this->validate($request,[
        //     'zip_code' => 'required',
        //     'email' => 'required',
        //     'phone' => 'required',
        //     'translates.ka.logo' => 'required|mimes:jpeg,jpg,png'
        // ]);

        $update = $contact->updateItem($request);

        if(!$update)
        {
            $this->error();
        }

        return $this->getResponse();
    }
}
