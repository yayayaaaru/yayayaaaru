<?php

declare(strict_types=1);

return [

    'services' => [

        'yagames' => [
            'endpoint' => env('YAGAMES_API_ENDPOINT', 'https://yandex.ru/games/api/catalogue/v2'),
        ],

    ],

];
