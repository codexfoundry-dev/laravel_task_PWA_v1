<?php

return [
    'vapid' => [
        'public_key' => env('VAPID_PUBLIC_KEY'),
        'private_key' => env('VAPID_PRIVATE_KEY'),
        'subject' => env('APP_URL', 'http://localhost'),
    ],

    'gcm' => [
        'key' => env('GCM_KEY'),
    ],
];