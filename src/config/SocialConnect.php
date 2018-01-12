<?php

return [
    'redirectUri' => 'http://127.0.0.1/auth/cb',
    'provider' => [
        'facebook' => [
            'applicationId' => '',
            'applicationSecret' => '',
            'scope' => ['email']
        ],
        'twitter' => [
            'applicationId' => '',
            'applicationSecret' => '',
        ],
        'google' => [
            'applicationId' => '',
            'applicationSecret' => '',
            'scope' => [
                'https://www.googleapis.com/auth/userinfo.email',
                'https://www.googleapis.com/auth/userinfo.profile'
            ]
        ],
        'paypal' => [
            'applicationId' => '',
            'applicationSecret' => '',
            'scope' => [
                'profile',
                'email',
                'address',
                'phone',
                'https://uri.paypal.com/services/paypalattributes'
            ]
        ],
        'vk' => [
            'applicationId' => '',
            'applicationSecret' => '',
            'scope' => ['email']
        ],
        'instagram' => [
            'applicationId' => '',
            'applicationSecret' => '',
        ],
        'slack' => [
            'applicationId' => '',
            'applicationSecret' => '',
            'scope' => [
                'identity.basic',
                'identity.email',
                'identity.team',
                'identity.avatar',
            ]
        ],
        'twitch' => [
            'applicationId' => '',
            'applicationSecret' => '',
            'scope' => ['user_read']
        ],
        'px500' => [
            'applicationId' => '',
            'applicationSecret' => ''
        ],
        'bitbucket' => [
            'applicationId' => '',
            'applicationSecret' => '',
            'scope' => ['account']
        ],
        'amazon' => [
            'applicationId' => '',
            'applicationSecret' => '',
            'scope' => ['profile']
        ],
        'gitlab' => [
            'applicationId' => '',
            'applicationSecret' => '',
            'scope' => ['read_user']
        ],
        'vimeo' => [
            'applicationId' => '',
            'applicationSecret' => '',
            'scope' => []
        ],

        'yandex' => [
            'applicationId' => '',
            'applicationSecret' => '',
            'scope' => []
        ],
        //http://api.mail.ru/sites/my/add
        'mail-ru' => [
            'applicationId' => '',
            'applicationSecret' => '',
            'scope' => []
        ],
        //http://api.mail.ru/sites/my/add
        'odnoklassniki' => [
            'applicationId' => '',
            'applicationSecret' => '',
            'applicationPublic' => '',
            'scope' => [
                'GET_EMAIL'
            ]
        ],
        'github' => [
            'applicationId' => '',
            'applicationSecret' => '',
        ],
        'steam' => [
            'applicationId' => '',
            'applicationSecret' => '',
            'scope' => []
        ],
        'tumblr' => [
            'applicationId' => '',
            'applicationSecret' => '',
            'scope' => []
        ],
        'pixelpin' => [
            'applicationId' => '',
            'applicationSecret' => '',
            'scope' => [
                'email'
            ]
        ],
        // https://discordapp.com/developers/applications/me
        'discord' => [
            'applicationId' => '',
            'applicationSecret' => '',
            'scope' => [
                'identify',
                'email'
            ]
        ],
        // https://apps.dev.microsoft.com/portal/register-app
        'microsoft' => [
            'applicationId' => '',
            'applicationSecret' => '',
            'scope' => [
                'wl.basic',
                'wl.birthday',
                'wl.emails'
            ]
        ],
        'smashcast' => [
            'applicationId' => '',
            'applicationSecret' => '',
            'scope' => []
        ],
        'steein' => [
            'applicationId' => '',
            'applicationSecret' => '',
            'scope' => [
                'users',
                'email'
            ]
        ]
    ]
];