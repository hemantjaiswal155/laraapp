<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * Validation rules for add customer
     *
     * @return array
     */
    public static function  validationRules()
    {
        return [
            'first_name'    => 'required|max:' . config('app.length.name') . '|regex:' . config('app.validation_patterns.name_server'),
            'last_name'     => 'required|max:' . config('app.length.name') . '|regex:' . config('app.validation_patterns.name_server'),
            'gender'        => 'required',
            'mobile'        => 'min:'.config('app.length.mobile_min').'|max:'.config('app.length.mobile_max').'|regex:'.config('app.validation_patterns.mobile'),
            'email'         => 'required|email|unique:customers',
            'country_id'    => 'required',
            'state_id'      => 'required'
        ];
    }


    /**
     * Validation messages
     *
     * @var array
     */
    public static $validationMessages = [
        'first_name.required'   => 'Please enter first name',
        'first_name.max'        => 'First name can be max 50 characters long',
        'first_name.regex'      => 'First name is invalid',
        'last_name.required'    => 'Please enter last name',
        'last_name.max'         => 'Last name can be max 50 characters long',
        'last_name.regex'       => 'Last name is invalid',
        'gender.required'       => 'Please select gender',
        'mobile.min'            => 'Mobile number can be minimum 7 digits long',
        'mobile.max'            => 'Mobile number can be max 10 digits long',
        'mobile.regex'          => 'Mobile number must be 7-11 digits',
        'email.required'        => 'Please enter email address',
        'email.email'           => 'Email address is invalid',
        'email.unique'          => 'This email address already exist',
        'country_id.required'   => 'Please select country',
        'state_id.required'     => 'Please select state',
    ];

    /**
     * Validation rules for udpate
     *
     * @param $id
     * @return array
     */
    public static function validationRulesForUpdate($id)
    {
        return [
            'first_name'            => 'required|max:' . config('app.length.name') . '|regex:' . config('app.validation_patterns.name_server'),
            'last_name'             => 'required|max:' . config('app.length.name') . '|regex:' . config('app.validation_patterns.name_server'),
            'gender'                => 'required',
            'mobile'                => 'min:'.config('app.length.mobile_min').'|max:'.config('app.length.mobile_max').'|regex:'.config('app.validation_patterns.mobile'),
            'email'                 => 'required|email|unique:customers,email,'.$id.',id',
            'country_id.required'   => 'Please select country',
            'state_id.required'     => 'Please select state',
        ];
    }
}
