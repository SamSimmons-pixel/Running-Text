<?php

return [
    'default_provider' => env('PRAYER_TIME_PROVIDER', 'aladhan'),
    'cache_ttl' => 86400, // seconds in a day

    // Predefined representative cities and their mappings
    'cities' => [
        'Jakarta' => [
            'name' => 'Jakarta',
            'timezone' => 'Asia/Jakarta',
            'myquran_id' => '1301',
        ],
        'Surabaya' => [
            'name' => 'Surabaya',
            'timezone' => 'Asia/Jakarta',
            'myquran_id' => '1638',
        ],
        'Medan' => [
            'name' => 'Medan',
            'timezone' => 'Asia/Jakarta',
            'myquran_id' => '0115',
        ],
        'Makassar' => [
            'name' => 'Makassar',
            'timezone' => 'Asia/Makassar',
            'myquran_id' => '2701',
        ],
        'Banjarmasin' => [
            'name' => 'Banjarmasin',
            'timezone' => 'Asia/Makassar',
            'myquran_id' => '2303',
        ],
        'Jayapura' => [
            'name' => 'Jayapura',
            'timezone' => 'Asia/Jayapura',
            'myquran_id' => '3211',
        ],
        'Ambon' => [
            'name' => 'Ambon',
            'timezone' => 'Asia/Jayapura',
            'myquran_id' => '3101',
        ],
    ],
];
