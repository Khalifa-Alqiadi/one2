<?php
$SERVER_NAME = (@$_SERVER['SERVER_NAME'] != "") ? @$_SERVER['SERVER_NAME'] : "localhost";
if (!checkdnsrr($SERVER_NAME, 'NS')) {
    $permissions = [
        'storage/framework/' => '',
        'storage/logs/' => '',
        'bootstrap/cache/' => '',
    ];
} else {
    $permissions = [
        'storage/framework/' => '755',
        'storage/logs/' => '755',
        'bootstrap/cache/' => '755',
        '../uploads/' => '755',
        '../uploads/banners' => '755',
        '../uploads/contacts' => '755',
        '../uploads/inbox' => '755',
        '../uploads/pattern' => '755',
        '../uploads/sections' => '755',
        '../uploads/settings' => '755',
        '../uploads/topics' => '755',
        '../uploads/users' => '755',
    ];
}

return [

    /*
    |--------------------------------------------------------------------------
    | Server Requirements
    |--------------------------------------------------------------------------
    |
    | This is the default Laravel server requirements, you can add as many
    | as your application require, we check if the extension is enabled
    | by looping through the array and run "extension_loaded" on it.
    |
    */
    'core' => [
        'minPhpVersion' => '8.1.2',
    ],

    'requirements' => [
        'openssl',
        'pdo',
        'mbstring',
        'tokenizer',
        'JSON',
        'cURL',
        'bcmath',
        'ctype',
        'xml',
        'fileinfo',
    ],

    /*
    |--------------------------------------------------------------------------
    | Folders Permissions
    |--------------------------------------------------------------------------
    |
    | This is the default Laravel folders permissions, if your application
    | requires more permissions just add them to the array list bellow.
    |
    */
    'permissions' => $permissions
];
