<?php

namespace App\Http\Requests;

use App\Exceptions\BusinessExceptions\ParamsErrorBusinessException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseFormRequest extends FormRequest
{
    /**
     * 判断用户是否有请求权限，通常用来处理细分权限，如部分用户有增删改，部分用户只能查看，区别与路由中间件
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
     * 验证失败，抛出业务异常，由业务异常统一输出结果
     * @throws ParamsErrorBusinessException
     */
    protected function failedValidation(Validator $validator)
    {
//        parent::failedValidation($validator);
        $message = $validator->errors()->all();
        throw new ParamsErrorBusinessException(implode('||',$message), 400, 400);
    }
}
