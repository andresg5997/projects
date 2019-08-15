<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
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
        switch ($this->method()) {

            case 'DELETE':
            {
                return [];
            }

            case 'POST':
            {
                if (str_contains($this->route()->getName(), 'footer')) {
                    return [
                        //
                        'slug'     => 'required|min:3|max:255|unique:pages',
                        'name'     => 'required|min:3|max:255|unique:pages',
                        'order'    => 'required|integer',
                    ];
                } else {
                    return [
                        //
                        'slug'     => 'required|min:3|max:255|unique:pages',
                        'name'     => 'required|min:3|max:255|unique:pages',
                        'order'    => 'required|integer',
                        'title'    => 'required|min:3',
                        'icon'     => 'required',
                        'content'  => 'required|min:3',
                    ];
                }
            }

            case 'PATCH':
            {
                $link = $this->url();
                $link_array = explode('/', $link);
                $id = end($link_array);

                if (str_contains($this->route()->getName(), 'footer')) {
                    return [
                        'slug'     => 'required|min:3|unique:pages,slug,'.$id,
                        'name'     => 'required|min:3|unique:pages,name,'.$id,
                        'order'    => 'required',
                    ];
                } else {
                    return [
                        'slug'     => 'required|min:3|unique:pages,slug,'.$id,
                        'name'     => 'required|min:3|unique:pages,name,'.$id,
                        'order'    => 'required',
                        'title'    => 'required|min:3|unique:pages,order,'.$id,
                        'icon'     => 'required',
                        'content'  => 'required|min:3',
                    ];
                }
            }

            default:break;
        }
    }
}
