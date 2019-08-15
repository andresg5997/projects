<?php

namespace App\Http\Requests\User;

use App\Attachment;
use Embed\Embed;
use Illuminate\Foundation\Http\FormRequest;

class UploadMediaRequest extends FormRequest
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
        //initialize with no rules
        $rules = [
            'files' => 'max:50',
        ];

        if ($this->method() == 'PATCH') {

            if ($this->route()->getName() == 'manage.update') {
                $rules = [
                    'category_id' => 'required|numeric',
                    'title'       => 'required|min:6|max:100|',
                ];
            } else {
                $rules = [
                    'category_id' => 'required|numeric',
                    'title'       => 'required|min:6|max:100|',
                    'slug'        => 'required|min:6|max:100',
                ];
            }
            
            return $rules;
        }

        if ($this->input('type') == 'video') {

        }

        if ($this->input('type') == 'picture') {
            $rules['main_image'] = 'required|image|mimes:png,jpg,jpeg,gif';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'url.required' => 'incorrect video url',
        ];
    }
}
