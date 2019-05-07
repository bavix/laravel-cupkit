<?php

return [

    'client_credentials' => \Bavix\CupKit\ClientCredentials::class,
    'identity' => \Bavix\CupKit\Identity::class,
    'client' => \Bavix\CupKit\Client::class,

    'base_url' => env('CDN_BASE_URL'),

    'client_id' => env('CDN_CLIENT_ID'),
    'client_secret' => env('CDN_SECRET'),

    'username' => env('CDN_USERNAME'),
    'password' => env('CDN_PASSWORD'),

    'buckets' => [

        //'wheels' => [
        //    [
        //        'name' => 'xs',
        //        'type' => 'fit',
        //        'width' => 250,
        //        'height' => 250,
        //        'quality' => 75,
        //        'color' => 'rgba(0,0,0,0)',
        //    ],
        //],

    ],

];
