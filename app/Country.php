<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * Get all country
     * @return array
     */
    public static function getAllCountry()
    {
        //Get all country
        $countries = self::orderBy('country_name')->pluck('country_name', 'id')->all();
        $default = ['' => 'Select country'];
        $countries = $default + $countries;
        return $countries;
    }

    /**
     * Validation rules for update country
     * @param $id
     * @return array
     */
    public static function validationRulesForUpdate($id)
    {
        return [
            'country_name' => 'required|regex:'. config('app.validation_patterns.other_name').'|unique:countries,country_name,' . $id.',id'
        ];
    }

    /**
     * Validation messages from update country
     * @var array
     */
    public static $validationMessagesForUpdate = [
        'country_name.required' => 'Please enter country name.',
        'country_name.regex'    => 'Please enter valid country name',
        'country_name.unique'   => 'This county is already available. Please choose different name.',
    ];
}
