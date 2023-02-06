<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            "name" => ["required"],
            // "start_project" => ["required"],
            "status" => ["required"],
            // "menber" => ["required", "numeric", "min:0", "not_in:0"],
            // "cost" => ["required", "numeric", "min:0", "not_in:0"],
        ];
    }
    public function messages()
    {
        return [
            "name.required" => "Tên dự án không được để trống!",
            // "start_project.required" => "Ngày bắt đầu dự án không được để trống!",
            "status.required" => "Trạng thái không được để trống!",
            // "menber.required" => "Số thành viên không được để trông!",
            // "cost.required" => "Chi phí không được để trống!",
            // "menber.min" => "Số thành viên không được có số âm!.",
            // "cost.min" => "Chi phí không được có số âm!.",

        ];
    }
}
