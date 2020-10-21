<?php


namespace App\Http\Requests;


class LoginRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|string|max:255|min:6',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',

        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'username' => 'Tài khoản không hợp lệ',
            'email' => 'Email không hợp lệ',
            'password' => 'Password không hợp lệ',
        ];
    }
}
