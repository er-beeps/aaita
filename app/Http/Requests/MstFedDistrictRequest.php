<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class MstFedDistrictRequest extends FormRequest
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
        $id_check = $this->request->get('id') ? ",".$this->request->get('id') : "";
        return [
            'code'=>'max:20',
            'name_lc'=>'required|max:200|unique:mst_fed_district,name_lc'.$id_check,
            'name_en'=>'required|max:200|unique:mst_fed_district,name_en'.$id_check,
            'remarks' => 'max:500',
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
            'name_lc.unique'=>trans('validationMessage.name_lc'),
            'name_en.unique'=>trans('validationMessage.name_en'),
            'name_lc.required'=>'जिल्लाको नाम आवश्यक छ',
            'name_en.required'=>'जिल्लाको Name आवश्यक छ',
        ];
    }
}
