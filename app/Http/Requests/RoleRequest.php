<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class RoleRequest extends FormRequest
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
            "display_name" => "required",
        ];
        if (Request::routeIs("role.post.create")) {
            $rules['nameRole'] = "required";
        }
        return $rules;
    }
    public function messages()
    {
        $messages = [
            "display_name.required" => "Tên hiển thị không được để trống!"
        ];
        if (Request::routeIs("role.post.create")) {
            $messages["nameRole.required"] = "Tên viết tắt không được để trống!";
        }
        return $messages;
    }
}
