<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'lastName' => 'required|min:5|max:255',
            'firstName' => 'required|min:5|max:255',
            'birthDate' => 'required',
            'departmentId' => 'required',
            'sectionId' => 'required',
            'positionId' => 'required',
            'workStatus' => 'required|min:5|max:255',
            'remarks' => 'required|min:5|max:255',
            'encryptCode' => 'required|min:5|max:255',
            'civilStatus' => 'required',
            'citizenShip' => 'required',
            'country' => 'required',
            'sex' => 'required',
            'empPrint' => 'required',
            'smallPrint' => 'required'
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
