<?php

namespace App\Http\Requests\Api;


class HelperDictionaryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            //'code' => 'required',
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'code.required' => '编码缺失',
        ];
    }
}
