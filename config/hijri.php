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
            'alhabib_path' => 'Indonesia/Jakarta%2C+DKI+Jakarta/NT_xwh4TEsjFzMbm1XDJmDcWC/',
        ],
        'Cileungsi' => [
            'name' => 'Cileungsi',
            'timezone' => 'Asia/Jakarta',
            'myquran_id' => '1205',
            'alhabib_path' => 'Indonesia/Cileungsi%2C+Bogor%2C+Jawa+Barat/NT_9Ayt-ThhB5OM8YpUEh1wPA/',
        ],
        'Surabaya' => [
            'name' => 'Surabaya',
            'timezone' => 'Asia/Jakarta',
            'myquran_id' => '1638',
            'alhabib_path' => 'Indonesia/Surabaya%2C+Jawa+Timur/NT_KqsIlA5DG.IVkPgmV3B-6C/',
        ],
        'Medan' => [
            'name' => 'Medan',
            'timezone' => 'Asia/Jakarta',
            'myquran_id' => '0115',
            'alhabib_path' => 'Indonesia/Medan%2C+Sumatera+Utara/NM_234974868/',
        ],
        'Makassar' => [
            'name' => 'Makassar',
            'timezone' => 'Asia/Makassar',
            'myquran_id' => '2701',
            'alhabib_path' => 'Indonesia/Makassar%2C+Sulawesi+Selatan/NT_1CHpbj-YKibtLOUr7gMebB/',
        ],
        'Banjarmasin' => [
            'name' => 'Banjarmasin',
            'timezone' => 'Asia/Makassar',
            'myquran_id' => '2303',
            'alhabib_path' => 'Indonesia/Banjarmasin%2C+Kalimantan+Selatan/27353143/',
        ],
        'Jayapura' => [
            'name' => 'Jayapura',
            'timezone' => 'Asia/Jayapura',
            'myquran_id' => '3211',
            'alhabib_path' => 'Indonesia/Jayapura%2C+Papua/NT_-krA9OO83qDPqG2Ch2VSDB/',
        ],
        'Ambon' => [
            'name' => 'Ambon',
            'timezone' => 'Asia/Jayapura',
            'myquran_id' => '3101',
            'alhabib_path' => 'Indonesia/Ambon%2C+Maluku/27353102/',
        ],
    ],
];
