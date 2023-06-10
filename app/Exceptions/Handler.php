<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function invalidJson($request, \ValidationException|\Illuminate\Validation\ValidationException $exception)
    {
        $res_message = '';
        foreach($exception->errors() as $key => $error){
            if($key === array_key_last($exception->errors())){
                $res_message .= $error[0];
            }else{
                $res_message .= $error[0] . ' - ';
            }

        }
        return response()->json([
            'message' => $res_message,
            'errors' => $exception->errors(),
        ], $exception->status);
    }
}
