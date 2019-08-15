<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UsersRequest extends FormRequest
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
        $link = $this->url();
        $link_array = explode('/', $link);
        $id = end($link_array);

        return [
            'username' => 'required|max:50|alpha_num|unique:users,username,'.$id,
            'email'    => 'required|email|max:255|unique:users,email,'.$id,
            'password' => 'required|min:6',
            'type'     => 'required',
        ];
    }
}
