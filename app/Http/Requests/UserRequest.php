<?php

namespace App\Http\Requests;


class UserRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => 'required',
            'oldPassword' => 'required',
            'newPassword' => 'required'
        ];
    }

    /**
     * 获取已定义验证规则的错误消息
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'id.required' => 'id 必填',
            'oldPassword.required' => '旧密码必填',
            'newPassword.required' => '新密码必填'
        ];
    }
}
