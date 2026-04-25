<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sportmonks API
    |--------------------------------------------------------------------------
    | كل الإعدادات اللازمة لخدمة Sportmonks.
    | توكن الـ API يُقرأ من .env حفاظاً على أمان البيانات.
    */

    'token' => env('SPORTMONKS_TOKEN'),

    'base_url' => env('SPORTMONKS_BASE_URL', 'https://api.sportmonks.com/v3/football'),

    // مدة التخزين المؤقت بالثواني (افتراضي: 10 دقائق)
    'cache_ttl' => (int) env('SPORTMONKS_CACHE_TTL', 600),

    // عدد النتائج لكل صفحة
    'per_page' => (int) env('SPORTMONKS_PER_PAGE', 50),

    // أقصى عدد صفحات للجلب (حماية من الحلقات)
    'max_pages' => (int) env('SPORTMONKS_MAX_PAGES', 20),

    // قائمة الـ includes (مفصولة بفاصلة منقوطة حسب صيغة Sportmonks)
    'includes' => implode(';', [
        'state',
        'participants',
        'scores',
        'events.player',
        'events.type',
        'events.relatedPlayer',
        'lineups.player',
        'lineups.position',
        'statistics.type',
        'metadata',
        'periods',
        'tvStations.tvStation',
        'tvStations.country',
        'sidelined.player',
        'sidelined.sideline',
        'sidelined.type',
        'venue',
        'league',
    ]),

    // مهلة الاتصال بالثواني
    'timeout' => (int) env('SPORTMONKS_TIMEOUT', 30),
];
