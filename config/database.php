<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for database operations. This is
    | the connection which will be utilized unless another connection
    | is explicitly specified when you execute a query / statement.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Below are all of the database connections defined for your application.
    | An example configuration is provided for each database system which
    | is supported by Laravel. You're free to add / remove connections.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DB_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
            'busy_timeout' => null,
            'journal_mode' => null,
            'synchronous' => null,
        ],

        // Main/Default Database (Users, Authentication, etc.)
        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'opvenio_hr2'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        // Module 1 Database - Competency Management
        'competency_management' => [
            'driver' => 'mysql',
            'url' => env('MODULE1_DB_URL'),
            'host' => env('MODULE1_DB_HOST', '127.0.0.1'),
            'port' => env('MODULE1_DB_PORT', '3306'),
            'database' => env('MODULE1_DB_DATABASE', 'competency_managements'),
            'username' => env('MODULE1_DB_USERNAME', 'root'),
            'password' => env('MODULE1_DB_PASSWORD', ''),
            'unix_socket' => env('MODULE1_DB_SOCKET', ''),
            'charset' => env('MODULE1_DB_CHARSET', 'utf8mb4'),
            'collation' => env('MODULE1_DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        // Module 2 Database - Training Management
        'training_management' => [
            'driver' => 'mysql',
            'url' => env('MODULE2_DB_URL'),
            'host' => env('MODULE2_DB_HOST', '127.0.0.1'),
            'port' => env('MODULE2_DB_PORT', '3306'),
            'database' => env('MODULE2_DB_DATABASE', 'training_management'),
            'username' => env('MODULE2_DB_USERNAME', 'root'),
            'password' => env('MODULE2_DB_PASSWORD', ''),
            'unix_socket' => env('MODULE2_DB_SOCKET', ''),
            'charset' => env('MODULE2_DB_CHARSET', 'utf8mb4'),
            'collation' => env('MODULE2_DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        // Module 3 Database - Learning Management
        'learning_management' => [
            'driver' => 'mysql',
            'url' => env('MODULE3_DB_URL'),
            'host' => env('MODULE3_DB_HOST', '127.0.0.1'),
            'port' => env('MODULE3_DB_PORT', '3306'),
            'database' => env('MODULE3_DB_DATABASE', 'learning_management'),
            'username' => env('MODULE3_DB_USERNAME', 'root'),
            'password' => env('MODULE3_DB_PASSWORD', ''),
            'unix_socket' => env('MODULE3_DB_SOCKET', ''),
            'charset' => env('MODULE3_DB_CHARSET', 'utf8mb4'),
            'collation' => env('MODULE3_DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        // Module 4 Database - Succession Planning
        'succession_planning' => [
            'driver' => 'mysql',
            'url' => env('MODULE4_DB_URL'),
            'host' => env('MODULE4_DB_HOST', '127.0.0.1'),
            'port' => env('MODULE4_DB_PORT', '3306'),
            'database' => env('MODULE4_DB_DATABASE', 'succession_planning'),
            'username' => env('MODULE4_DB_USERNAME', 'root'),
            'password' => env('MODULE4_DB_PASSWORD', ''),
            'unix_socket' => env('MODULE4_DB_SOCKET', ''),
            'charset' => env('MODULE4_DB_CHARSET', 'utf8mb4'),
            'collation' => env('MODULE4_DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        // Module 5 Database - ESS
        'ess' => [
            'driver' => 'mysql',
            'url' => env('MODULE5_DB_URL'),
            'host' => env('MODULE5_DB_HOST', '127.0.0.1'),
            'port' => env('MODULE5_DB_PORT', '3306'),
            'database' => env('MODULE5_DB_DATABASE', 'ess'),
            'username' => env('MODULE5_DB_USERNAME', 'root'),
            'password' => env('MODULE5_DB_PASSWORD', ''),
            'unix_socket' => env('MODULE5_DB_SOCKET', ''),
            'charset' => env('MODULE5_DB_CHARSET', 'utf8mb4'),
            'collation' => env('MODULE5_DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'mariadb' => [
            'driver' => 'mariadb',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => '',
            'prefix_indexes' => true,
            // 'encrypt' => env('DB_ENCRYPT', 'yes'),
            // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run on the database.
    |
    */

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as Memcached. You may define your connection settings here.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug((string) env('APP_NAME', 'laravel')).'-database-'),
            'persistent' => env('REDIS_PERSISTENT', false),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];