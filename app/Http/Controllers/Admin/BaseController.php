<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;

class BaseController extends Controller
{
    public function __construct(){
        $local_lang_keys = array_keys(\LaravelLocalization::getSupportedLocales());

        View::share('Localization', $local_lang_keys);
    }
}
