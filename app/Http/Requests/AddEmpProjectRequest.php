<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AddEmpProjectRequest extends FormRequest
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
        if (Request::routeIs("project.addEmployee.post")) {
            return [
                "employee" => "required",
            ];
        }
        if (Request::routeIs("employee.post.percent")) {
            return [
                "percent.*" => "required",
            ];
        }
    }
    public function messages()
    {
        if (Request::routeIs("project.addEmployee.post")) {
            return [
                "employee.required" => "Vui lòng chọn nhân viên!",
            ];
        }
        if (Request::routeIs("employee.post.percent")) {
            return [
                "percent.*.required" => "Vui lòng nhập phần trăm cho từng dự án!",
            ];
        }
    }
}
