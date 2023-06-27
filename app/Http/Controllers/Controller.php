<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected int $status_code = 200;
    protected array $data = [];
    use AuthorizesRequests, ValidatesRequests;

    public function getResponse() :object
    {
        return response()->json($this->data, $this->status_code);
    }

    public function error() :void
    {
        $this->data['message'] = 'დაფიქსირდა შეცდომა';
        $this->status_code = 500;
    }
}
