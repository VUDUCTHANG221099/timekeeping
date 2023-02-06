<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PassConfirmationRequest extends FormRequest
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
            'password' => 'required|min:8|max:20',
            'password_confirmation' => 'required|required_with:password|same:password'
        ];
    }
    public function messages(){
        return [
            "password.required" =>"Mật khẩu không được để trống!",
            "password.min"=>"Mật khẩu phải có ít nhất :min ký tự!",
            "password.max"=>"Mật khẩu không được lớn hơn :max ký tự!",
            "password_confirmation.required"=>"Xác nhận mật khẩu không được để trống!",
            "password_confirmation.same"=>"Xác nhận mật khẩu và mật khẩu không khớp!",
        ];
    }

}
