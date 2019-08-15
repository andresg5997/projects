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
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    // abe466d5ea3c5043ee54a2a97e423ed2341340d8
    'sparkpost' => [
        'secret' => 'b2e55668de481222a5d486308214081c7579831b',
    ],

    'stripe' => [
        'model'  => App\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    // social login
    'facebook' => [
        'client_id'     => '',
        'client_secret' => '',
        'redirect'      => '',
    ],

    'twitter' => [
        'client_id'     => '',
        'client_secret' => '',
        'redirect'      => '',
    ],

];
