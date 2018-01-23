<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'facebook' => [
        'client_id'     => '164706484171217',
        'client_secret' => 'cbc44f0b4b1eca5b72d22a968ad12949',
        'redirect'      => 'http://laraapp.local.com/auth/facebook/login',
    ],

    'linkedin' => [
        'client_id'     => '81uzk0q8jkg3xy',
        'client_secret' => '9wTAHjbELahgTel5',
        'redirect'      => 'http://laraapp.local.com/auth/linkedin/login',
    ],

    'twitter' => [
        'client_id'     => 'lzQmgFHSgBZL6oW52BTfO63o3',
        'client_secret' => 'GytwSYbplf0rijvwuUItCzY5s03FvAxoKbfsT4l0QwwvvasX3k',
        'redirect'      => 'http://laraapp.local.com/auth/twitter/login',
    ],

    'google' => [
        'client_id'     => '704327090549-kcfiid393vsl8jtmtqchjk28keu8ajng.apps.googleusercontent.com',
        'client_secret' => 'YAhbYtdALr7KPHgNOl6NGzcc',
        'redirect'      => 'http://laraapp.local.com/auth/google/login',
    ]

];
