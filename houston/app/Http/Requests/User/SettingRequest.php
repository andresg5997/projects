<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
            if ($this->route()->getName() == 'settings.avatar.update') {
                return [
                    'avatar' => 'required|image|mimes:jpg,jpeg,png',
                ];
            }

            if ($this->route()->getName() == 'settings.password.update') {
                return [
                    'password' => 'required|min:6|confirmed',
                ];
            }

            if ($this->route()->getName() == 'user.settings.affiliate.update') {
                return [

                ];
            }

            if ($this->route()->getName() == 'settings.notifications.update') {
                return [

                ];
            }

            if ($this->route()->getName() == 'settings.profile.update') {
                return [
                    'username' => 'required|max:50|alpha_num|unique:users,username,'.$this->user()->id,
                    'email'    => 'required|email|max:255|unique:users,email,'.$this->user()->id,
                ];
            }
        }
    }
}
