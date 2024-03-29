<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaasMachineryRequest extends FormRequest
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
            // 'noOfStreet' => 'required',
            // 'barangayId' => 'required',
            'isActive' => 'required',
            'pin' => 'unique:faas_lands,pin,'.$this->id,
            // 'assessmentType' => 'required',
            // 'assessmentEffectivity' => 'required',
            // 'assessmentEffectivityValue' => 'required',
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
