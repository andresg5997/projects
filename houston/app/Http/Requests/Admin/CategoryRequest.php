<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        if ($this->method() == 'POST') {
            return [
                //
                'name' => 'required|unique:categories',
            ];
        }
        if ($this->method() == 'PATCH') {
            $link = $this->url();
            $link_array = explode('/', $link);
            $id = end($link_array);

            return [
                //
                'name' => 'required|min:3|unique:categories,name,'.$id,
            ];
        }
    }
}
