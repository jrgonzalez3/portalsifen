<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SIFEN API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for SIFEN (Sistema de Facturación Electrónica Nacional)
    | API integration with support for multiple environments.
    |
    */

    'environment' => env('SIFEN_ENVIRONMENT', 'dev'),

    'environments' => [
        'test' => [ // Testing environment
            'url' => env('URL_TESTING', 'http://10.99.99.63:37100'),
            'credentials' => [
                'idCsc' => trim(env('IDCSC_TEST', '0001'), " \",\t\n\r\0\x0B"),
                'csc' => trim(env('CSC_TEST', ''), " \",\t\n\r\0\x0B"),
                'nombreCertificado' => trim(env('NOMBRE_CERTIFICADO_TEST', ''), " \",\t\n\r\0\x0B"),
                'claveCertificado' => trim(env('CLAVE_CERTIFICADO_TEST', ''), " \",\t\n\r\0\x0B"),
                'id' => '001',
            ],
        ],
        'prod' => [ // Production environment
            'url' => env('URL_PROD', 'http://192.168.77.74:37100'),
            'credentials' => [
                'idCsc' => trim(env('IDCSC_PROD', '0001'), " \",\t\n\r\0\x0B"),
                'csc' => trim(env('CSC_PROD', ''), " \",\t\n\r\0\x0B"),
                'nombreCertificado' => trim(env('NOMBRE_CERTIFICADO_PROD', ''), " \",\t\n\r\0\x0B"),
                'claveCertificado' => trim(env('CLAVE_CERTIFICADO_PROD', ''), " \",\t\n\r\0\x0B"),
                'id' => '001',
            ],
        ],
    ],

    'timeout' => 30,
    'retry_times' => 3,
    'retry_delay' => 1000, // milliseconds

    'auto_login' => [
        'enabled' => env('AUTO_LOGIN_ENABLED', false),
        'email' => env('AUTO_LOGIN_EMAIL'),
        'password' => env('AUTO_LOGIN_PASSWORD'),
    ],

];
