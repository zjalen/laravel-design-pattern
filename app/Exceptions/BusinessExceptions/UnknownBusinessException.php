<?php


namespace App\Exceptions\BusinessExceptions;

/**
 * 未知异常
 * Class UnknownBusinessException
 * @package App\Exceptions\BusinessExceptions
 */
class UnknownBusinessException extends BaseBusinessException
{
    /**
     * 未知异常
     * UnknownBusinessException constructor.
     * @param string $message 异常信息
     * @param int $code 异常码
     * @param int $statusCode http 状态码
     */
    public function __construct(string $message = '未知的异常', int $code = 400, int $statusCode = 400)
    {
        parent::__construct($message, $code, $statusCode);
    }
}
