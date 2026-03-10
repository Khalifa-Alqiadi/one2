<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    // in production will use = "../../uploads/",
    'uploads_path' => trim(env('LOCAL_UPLOADS_PATH')) !== '' ? env('LOCAL_UPLOADS_PATH') : str_replace('/core/public','',public_path('uploads')),

    // Set allowed file types

    'allowed_image_types' => "png,gif,jpg,jpeg,svg,webp",

    'allowed_video_types' => "mp4,ogv,webm",

    'allowed_audio_types' => "wav,mp3",

    'allowed_file_types' => "png,gif,jpg,jpeg,svg,,webp,psd,pdf,doc,docx,txt,rtf,xls,xlsx,ppt,pptx,mp3,wav,mp4,ogv,webm,zip,rar",

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [
        'local' => [
            'driver' => 'local',
            // in production will use = "../../uploads/",
            'root' => trim(env('LOCAL_UPLOADS_PATH')) !== '' ? env('LOCAL_UPLOADS_PATH') : str_replace('/core/public','',public_path('uploads')),
            'url' => trim(env("APP_URL"), "/").'/files/',
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => trim(env("APP_URL"), "/").'/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
