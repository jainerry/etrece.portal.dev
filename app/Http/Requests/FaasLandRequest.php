<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaasLandRequest extends FormRequest
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
            'primaryOwnerId' => 'required',
            'ownerAddress' => 'required',
            'noOfStreet' => 'required',
            'barangayId' => 'required',
            'cityId' => 'required',
            'provinceId' => 'required',
            'isActive' => 'required',
            'assessmentStatusId' => 'required',
            'assessmentType' => 'required',
            'assessmentEffectivity' => 'required',
            'assessmentEffectivityValue' => 'required',
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
