<?php

namespace App\Http\Requests;

use App\Models\BusinessActivity;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\BusinessType;

class BusinessProfilesRequest extends FormRequest
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
            'business_name' => 'required',
            'property_owner' => 'required',
            'lessor_name' => 'exclude_if:property_owner,N|required',
            "corp_type" => function ($attribute, $value, $fail) {
                $bType = BusinessType::where("id", $this->bus_type)->get()->first();

                if ($bType != null) {
                    if ($bType->corporation == 1 && ($value == null || $value == "")) {
                        $fail('The Corp.Type is required. Business Type is a corporation');
                    }
                }
            },
            "other_buss_type" => function ($attribute, $value, $fail) {
                $bAct = BusinessActivity::where("id", $this->business_activity_id)->first();

                if ($bAct != null) {
                    if ($bAct->open == 1 && ($value == null || $value == "")) {
                        $fail('The Other Buss. Type is required. Business Activity is set as others');
                    }
                }
            },
            "certificate" => "exclude_if:tax_incentives,N|required",
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
            "corp_type" => "Corp. Type"
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
