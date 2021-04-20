<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;

abstract class BaseApiController extends Controller
{

    /**
     * 接口顺利完成后成功返回
     * @param null $data
     * @param int $code
     * @param int $statusCode
     * @return mixed
     */
    public function success($data = null, $code = 1, $statusCode = 200)
    {
        return Response::success($data, $code, $statusCode);
    }

    /**
     * 接口异常返回信息
     * @param null $errorMessage
     * @param int $code
     * @param int $statusCode
     * @return mixed
     */
    public function fail($errorMessage = null, $code = 400, $statusCode = 400)
    {
        return Response::fail($errorMessage, $code, $statusCode);
    }
}
