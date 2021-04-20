<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // 全局异常报告，return false 时终止报告链
        $this->reportable(function (Throwable $e) {
//            Log::error('ExceptionHandler: '.$e->getMessage());
//            return false;
        });
        // 全局异常渲染
        $this->renderable(function (Throwable $e, $request) {
            $response = parent::convertExceptionToResponse($e);
            return Response::fail($e->getMessage(), $e->getCode(), $response->getStatusCode());
        });
    }
}
