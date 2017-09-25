<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 12/29/2016
 * Time: 4:15 PM
 */

namespace JP_COMMUNITY\Http\Requests;

use JP_COMMUNITY\Http\Requests\Request;

class CreateUserRequest extends Request
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
        return [
            'username' => 'required|min:3|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3|max:50',
            'repassword' => 'required|same:password',
        ];
    }

}