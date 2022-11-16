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
            'lastName' => 'required',
            'firstName' => 'required',
            'birthDate' => 'required',
            // 'birthPlace' => 'required',
            // 'citizenShipAcquisition' => 'required',
            // 'officeId' => 'required',
            // 'sectionId' => 'required',
            // 'positionId' => 'required',
            // 'appointmentId' => 'required',
            // 'civilStatus' => 'required',
            // 'citizenShip' => 'required',
            // 'sex' => 'required',
            'isActive' => 'required'
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
