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

    'credentials' => [
        'idCsc' => env('SIFEN_ID_CSC', '0001'),
        'csc' => env('SIFEN_CSC'),
        'claveCertificado' => env('SIFEN_CLAVE_CERTIFICADO'),
        'nombreCertificado' => env('SIFEN_NOMBRE_CERTIFICADO'),
        'id' => env('SIFEN_ID', '001'),
    ],

    'environments' => [
        'dev' => [
            'kude_url' => env('SIFEN_DEV_KUDE_URL', 'http://10.99.99.56:37100/consultakude/'),
            'ruc_url' => env('SIFEN_DEV_RUC_URL', 'http://10.99.99.56:37100/consultaruc/'),
            'lote_url' => env('SIFEN_DEV_LOTE_URL', 'http://10.99.99.56:37100/consultalote/'),
            'cdc_url' => env('SIFEN_DEV_CDC_URL', 'http://10.99.99.56:37100/consultacdc/'),
        ],
        'pruebas' => [
            'kude_url' => env('SIFEN_PRUEBAS_KUDE_URL', 'http://10.99.99.63:37100/consultakude/'),
            'ruc_url' => env('SIFEN_PRUEBAS_RUC_URL', 'http://10.99.99.63:37100/consultaruc/'),
            'lote_url' => env('SIFEN_PRUEBAS_LOTE_URL', 'http://10.99.99.63:37100/consultalote/'),
            'cdc_url' => env('SIFEN_PRUEBAS_CDC_URL', 'http://10.99.99.63:37100/consultacdc/'),
        ],
        'prod' => [
            'kude_url' => env('SIFEN_PROD_KUDE_URL', 'http://192.168.77.74:37100/consultakude/'),
            'ruc_url' => env('SIFEN_PROD_RUC_URL', 'http://192.168.77.74:37100/consultaruc/'),
            'lote_url' => env('SIFEN_PROD_LOTE_URL', 'http://192.168.77.74:37100/consultalote/'),
            'cdc_url' => env('SIFEN_PROD_CDC_URL', 'http://192.168.77.74:37100/consultacdc/'),
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
