<?php

namespace App\Exceptions\BusinessExceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

abstract class BaseBusinessException extends Exception
{
    /**
     * @var int
     */
    private $statusCode;

    public function __construct(string $message, int $code, int $statusCode = 400)
    {
        parent::__construct($message, $code);
        $this->statusCode = $statusCode;
    }

    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;
    }

    private function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * 单独报告异常
     * true 则拦截处理
     * false 则不处理，由全局 Handler 处理
     * @return ?bool
     */
    public function report(): ?bool
    {
        Log::debug('BaseBusinessException: '.$this->getMessage());
        return false;
    }

    /**
     * 渲染异常
     */
    public function render()
    {
        // 对详细的错误信息，进行记录，但是不返回给前端
        return Response::fail($this->getMessage(), $this->getCode(), $this->getStatusCode());
    }
}
