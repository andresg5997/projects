<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactUsRequest extends FormRequest
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
        if ($this->route()->getName() == 'contact.post.message') {
            return [
                //
                'name'                 => 'required|string|min:3|max:255',
                'email'                => 'required|email|max:255',
                'title'                => 'required|string|min:3|max:255',
                'message'              => 'required|string|min:3|max:2000',
                'g-recaptcha-response' => 'required|captcha',

            ];
        } elseif ($this->route()->getName() == 'dmca.post.message') {
            return [
                //
                'is-owner'             => 'required',
                'email'                => 'required|email|max:255',
                'name'                 => 'required|string|min:3|max:255',
                'rightsholder-name'    => 'required|string|min:3|max:255',
                'rightsholder-country' => 'required|string|min:3|max:255',
                'company'              => 'required|string|min:3|max:255',
                'phone'                => 'required|string|min:3|max:255',
                'job'                  => 'required|string|min:3|max:255',
                'country'              => 'required|string|min:3|max:255',
                'city'                 => 'required|string|min:3|max:255',
                'address'              => 'required|string|min:3|max:255',
                'zip'                  => 'required|string|min:3|max:255',
                'infringing-urls'      => 'required|string|min:3|max:5000',
                'g-recaptcha-response' => 'required|captcha',
                'confirm'              => 'required',

            ];
        }
    }
}
