<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMediaRequest extends FormRequest
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
        if ($this->method() == 'PATCH') {
            if ($this->route()->getName() == 'media.update') {
                return [
                    'title'       => 'min:6|max:100',
                    'description' => 'min:6|max:25000',
                    'thumbnail'   => 'image|mimes:jpg,jpeg,png',
                ];
            }
        }
    }
}
