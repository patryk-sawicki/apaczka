<?php
return [
    'app_id' => env('APACZKA_APP_ID'),
    'app_secret' => env('APACZKA_APP_SECRET'),
    'app_url' => env('APACZKA_APP_URL', 'https://www.apaczka.pl/api/v2/'),

    /*In seconds, max 1800*/
    'expires_time' => env('APACZKA_EXPIRES_TIME'),
];
