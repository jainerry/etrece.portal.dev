<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuildingProfileRequest extends FormRequest
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
            'isActive' => 'required',
            'primary_owner' => 'required',
            'ownerAddress' => 'required',
            'no_of_street' => 'required',
            'barangay_id' => 'required',
            'assessmentType' => 'required',
            'assessmentEffectivity' => 'required',
            'assessmentEffectivityValue' => 'required',
            'isActive' => 'required',
            'ARPNo' => 'max:25',
            'transactionCode' => 'max:25',
            'tel_no' => 'max:25',
            'owner_tin_no' => 'max:25',
            'admin_tel_no' => 'max:25',
            'admin_tin_no' => 'max:25',
            'oct_tct_no' => 'max:25',
            'lot_no' => 'max:25',
            'survey_no' => 'max:25',
            'block_no' => 'max:25',
            'building_permit_no' => 'max:25',
            'TDNo' => 'max:25'
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
