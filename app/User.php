<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
                          'name',
                          'email',
                          'password',
                          'country_id',
                          'state_id',
                          'verify_token',
                          'verify_send_on'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
                        'password',
                        'remember_token',
    ];

    /**
     * Set password rules
     *
     * @return array
     */
    public static function passwordRules()
    {
        return [
            'password' => 'min:'.config('app.length.password_min').'|max:'.config('app.length.password_max').'|confirmed'];
    }

    private static function validationRules()
    {
        return [
            'name'          => 'required|max:'.config('app.length.name').'|regex:'.config('app.validation_patterns.name_server'),
            'country_id'    => 'required',
            'state_id'      => 'required'
        ];
    }

    /**
     * Validation rules for add user
     *
     * @return array
     */
    public static function validationRulesForAddUser()
    {
        $rules['first_name']     = 'required';
        $rules['last_name']     = 'required';
        $rules['address']     = 'required';
        $rules['contact_number']     = 'required';
        $rules['email']     = 'required|email|unique:users';
        $rules['password']  = 'required|min:'.config('app.length.password_min').'|max:'.config('app.length.password_max').'|confirmed';
        return $rules;
    }

    public static function validationRulesForUpdateUser()
    {
        $rules['first_name']     = 'required';
        $rules['last_name']     = 'required';
        $rules['address']     = 'required';
        $rules['contact_number']     = 'required';
        
        return $rules;
    }

    /**
     * @return array
     * validation rules for update user
     */
    public static function validationRulesForUpdate()
    {
        return self::validationRules() + self::passwordRules();
    }

    /**
     * Validation messages
     *
     * @var array
     */
    public static $validationMessages = [
        'name.required'                     => 'Please enter name',
        'name.max'                          => 'Name can be max 50 characters long',
        'name.regex'                        => 'Name is invalid',
        'email.required'                    => 'Please enter email address',
        'email.email'                       => 'Email address is invalid',
        'email.unique'                      => 'This email address already exist',
        'old_password.required'             => 'Please enter old password',
        'old_password.old_password'         => 'Your old password is incorrect',
        'password.required'                 => 'Please enter password',
        'password.min'                      => 'Password can be minimum 6 characters long',
        'password.max'                      => 'Password can be max 20 characters long',
        'password.confirmed'                => 'Password and confirm password do not match',
        'password_confirmation.required'    => 'Please enter confirm password',
        'country_id.required'               => 'Please select country',
        'state_id.required'                 => 'Please select state',
    ];

    public function addNew($input)
    {
        $check = static::where('facebook_id',$input['facebook_id'])->first();

        if(is_null($check)){
            return static::create($input);
        }

        return $check;
    }
}
