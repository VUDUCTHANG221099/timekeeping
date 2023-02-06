<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PositionRequest extends FormRequest
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
            "display_name" => "required"
        ];
        if (Request::routeIs("position.post.create")) {
            $rules["namePosition"] = "required";
        }
        return $rules;
    }
    public function messages()
    {
        $messages = [
            "display_name.required" => "Tên hiển thị không được để trống!",
        ];
        if (Request::routeIs("position.post.create")) {
            $messages['namePosition.required'] = "Tên viết tắt không được để trống!";
        }
        return $messages;
    }
}
