<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class EmployeeRequest extends FormRequest
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
        $rules = [
            "fullname" => ["required", "max:100"],
            // "birthday" => ["required"],
            // "gender" => ["required"],
            // "phone" => ["required", "numeric", "min:0", "not_in:0"],
            // "address" => ["required"],
            "roles" => ["required"],
            // "positions" => ["required"],
            // "salary" => ["required", "numeric", "min:0", "not_in:0"],
        ];
        if (Request::routeIs("employee.post.create")) {

            $rules['password_confirmation'] = ["required","required_with:password","same:password"];
            $rules['email'] = ["required", "email", "max:100", "unique:users"];
            $rules['password'] = ["required", "min:8", "max:20"];
        }

        return $rules;
    }
    public function messages()
    {
        $messages = [
            "fullname.required" => "Họ và tên không được để trống!",
            "fullname.max" => "Họ và tên không được lớn hơn :max ký tự!",
            // "birthday.required" => "Ngày sinh không được để trống!",
            // "gender.required" => "Giới tính không được để trống!",
            // "phone.required" => "Số điện thoại không được để trống!",
            // "phone.min" => "Số điện thoại không được nhấp giá trị âm!",
            // "address.required" => "Địa chỉ không được để trống!",
            "roles.required" => "Vai trò không được để trống!",
            // "positions.required" => "Chức vụ không được để trống!",
            // "salary.required" => "Số lương không được để trống!",
            // "salary.min" => "Lương không được nhấp giá trị âm!",

        ];
        if (Request::routeIs("employee.post.create")) {
            $messages["email.required"] = "Email không được để trống!";
            $messages["email.email"] = "Sai định dạng email!";
            $messages["email.max"] = "Email không được lớn hơn :max ký tự!";
            $messages["email.unique"] = "Email đã tồn tại!";
            $messages["password.required"] = "Mật khẩu không được để trống!";
            $messages["password.min"] = "Mật khẩu phải có ít nhất :min ký tự!";
            $messages["password.max"] = "Mật khẩu không được lớn hơn :max ký tự!";
            $messages["password_confirmation.required"]="Xác nhận mật khẩu không được để trống!";
            $messages["password_confirmation.same"]="Xác nhận mật khẩu và mật khẩu không khớp!";
        }

        return $messages;
    }
}
