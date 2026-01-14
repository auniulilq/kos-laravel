<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // WhatsApp provider (Fonnte)
    'whatsapp' => [
        'provider' => 'fonnte',
        'api_url' => env('FONNTE_API_URL', 'https://api.fonnte.com/send'),
         'api_key' => env('FONNTE_API_KEY'),
     ],


    'google_ai' => [
        'api_key' => env('GOOGLE_API_KEY'),
        'model' => env('GOOGLE_AI_MODEL', 'gemini-1.5-flash-latest'),
        'system_prompt' => "Anda adalah Asisten Digital Kosan bernama Kosan AI. Tugas Anda adalah memberikan informasi kos kepada penyewa. Bersikaplah sopan, informatif, dan ringkas. Dilarang memberikan informasi di luar data yang saya berikan. Jika ditanyakan tentang masalah pembayaran, perbaikan mendesak, atau negosiasi harga, arahkan pengguna untuk menekan tombol 'Hubungi Bu Kos Langsung'. Kosan ini memiliki fasilitas: [Daftar Fasilitas], Peraturan Utama: [Daftar Peraturan], dan Harga dimulai dari [Harga Termurah].",
    ],

    'midtrans' => [
        'server_key' => env('MIDTRANS_SERVER_KEY'),
        'client_key' => env('MIDTRANS_CLIENT_KEY'),
        'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
        'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
        'is_3ds' => env('MIDTRANS_IS_3DS', true),
    ],
];
