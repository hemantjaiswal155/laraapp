<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    /**
     * Get all state by country id
     * @return array
     */
    public static function getSateByCountry($countryId)
    {
        //Get all state by country id
        $states = self::orderBy('state_name')
                            ->where('country_id', $countryId)
                            ->pluck('state_name', 'id')
                            ->all();
        $default = ['' => 'Select state'];
        $states = $default + $states;
        return $states;
    }

    /**
     * Validation rules for update state
     * @param $id
     * @return array
     */
    public static function validationRulesForUpdate($id)
    {
        return [
            'state_name' => 'required|regex:'. config('app.validation_patterns.other_name').'|unique:states,state_name,' . $id.',id'
        ];
    }

    /**
     * Validation messages from update state
     * @var array
     */
    public static $validationMessagesForUpdate = [
        'state_name.required' => 'Please enter state name.',
        'state_name.regex'    => 'Please enter valid state name',
        'state_name.unique'   => 'This state is already available. Please choose different name.',
    ];
}
