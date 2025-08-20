<?php

return [
    'oracle' => [
    'driver' => 'oracle',
    'tns' => env('DB_TNS', ''),
    'host' => env('DB_HOST2', ''),
    'port' => env('DB_PORT2', '1521'),
    'database' => env('DB_DATABASE2', ''),
    'service_name' => env('DB_SERVICE_NAME', ''),
    'username' => env('DB_USERNAME2', ''),
    'password' => env('DB_PASSWORD2', ''),
    'charset' => env('DB_CHARSET', 'AL32UTF8'),
    'prefix' => env('DB_PREFIX', ''),
    'prefix_schema' => env('DB_SCHEMA_PREFIX', ''),
    'edition' => env('DB_EDITION', 'ora$base'),
    'server_version' => env('DB_SERVER_VERSION', '19c'),
    'load_balance' => env('DB_LOAD_BALANCE', 'yes'),
    'max_name_len' => env('ORA_MAX_NAME_LEN', 30),
    'dynamic' => [],
    'sessionVars' => [
        'NLS_TIME_FORMAT' => 'HH24:MI:SS',
        'NLS_DATE_FORMAT' => 'YYYY-MM-DD HH24:MI:SS',
        'NLS_TIMESTAMP_FORMAT' => 'YYYY-MM-DD HH24:MI:SS',
        'NLS_TIMESTAMP_TZ_FORMAT' => 'YYYY-MM-DD HH24:MI:SS TZH:TZM',
        'NLS_NUMERIC_CHARACTERS' => '.,',
    ],
    'options' => [
        PDO::ATTR_PERSISTENT => false, // Conexión persistente
    ],
    ],

    'oracle3' => [
    'driver' => 'oracle',
    'tns' => env('DB_TNS', ''),
    'host' => env('DB_HOST3', ''),
    'port' => env('DB_PORT3', '1521'),
    'database' => env('DB_DATABASE3', ''),
    'service_name' => env('DB_SERVICE_NAME3', ''),
    'username' => env('DB_USERNAME3', ''),
    'password' => env('DB_PASSWORD3', ''),
    'charset' => env('DB_CHARSET', 'AL32UTF8'),
    'prefix' => env('DB_PREFIX', ''),
    'prefix_schema' => env('DB_SCHEMA_PREFIX', ''),
    'edition' => env('DB_EDITION', 'ora$base'),
    'server_version' => env('DB_SERVER_VERSION', '19c'),
    'load_balance' => env('DB_LOAD_BALANCE', 'yes'),
    'max_name_len' => env('ORA_MAX_NAME_LEN', 30),
    'dynamic' => [],
    'sessionVars' => [
        'NLS_TIME_FORMAT' => 'HH24:MI:SS',
        'NLS_DATE_FORMAT' => 'YYYY-MM-DD HH24:MI:SS',
        'NLS_TIMESTAMP_FORMAT' => 'YYYY-MM-DD HH24:MI:SS',
        'NLS_TIMESTAMP_TZ_FORMAT' => 'YYYY-MM-DD HH24:MI:SS TZH:TZM',
        'NLS_NUMERIC_CHARACTERS' => '.,',
    ],
    'options' => [
        PDO::ATTR_PERSISTENT => false, // Conexión persistente
    ],
    ],

];
