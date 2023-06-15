<?php

namespace App\Http\Controllers\Admin;

use App\Helper\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FileUploadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $this->validate($request,[
            'file' => 'required',
            'destination' => 'required'
        ]);
            
        $data['message'] = 'Success';

        $file = app(File::class)->uploadFile($request->file, $request->destination);

        $data['file'] = env('APP_URL') . '/' . $file;

        return response()->json($data, 200);
    }
}
