<?php

return [
    'Minify' => [
        'min' => false,
        'ver' => '0.0.1'
    ],
    'Logo' => [
        'Favicon' => '/img/favicon.ico',
    ],
    'LanguageList' => [
        LANGUAGE_VIETNAMESE => 'Vietnamese',
        LANGUAGE_ENGLISH => 'English',
        LANGUAGE_CHINESE => 'Chinese',
    ],
    'LocaleList' => [
        LANGUAGE_VIETNAMESE => 'vi_VN',
        LANGUAGE_ENGLISH => 'en_US',
    ],
    'DefaultLanguage' => LANGUAGE_VIETNAMESE,
    'Upload' => [
        'TmpFolder' => WWW_ROOT . 'tmp/',
        'CacheFolder' => WWW_ROOT . 'uploads/cache/',
        'PhotoFolder' => WWW_ROOT . 'uploads/photos/',
    ],
    'photoUpload' => [
        'uploadAccept' => [
            'image/jpg',
            'image/jpeg',
            'image/png',
            'image/gif',
        ],
        'extensions' => [
            'jpg',
            'jpeg',
            'png',
            'gif',
        ],
    ],
    'excelUpload' => [
        'uploadAccept' => [
            '.csv',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ],
        'extensions' => [
            'csv',
            'xls',
            'xlsx',
        ],
    ],
    'pdfUpload' => [
        'uploadAccept' => [
            '.pdf',
            'application/pdf',
        ],
        'extensions' => [
            'pdf',
        ],
    ],
    'mp3Upload' => [
        'uploadAccept' => [
            '.mp3',
            'audio/mpeg3',
            'audio/mpeg',
        ],
        'extensions' => [
            'mp3',
        ],
    ],
    'videoExtensions' => [
        'mp4',
        'mov',
        'avi',
        'wmv',
    ],
    'GoogleMap' => [
        'ApiKey' => 'AIzaSyAs9mLfOVae-kP6i2YgLM3JRPT5mnxjOm0',
        'DefaultCoordinate' => [
            'Longtitude' => 106.69898560000001,
            'Latitude' => 10.779853,
        ],
    ],
];
