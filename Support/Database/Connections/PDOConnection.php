<?php

namespace Plugin\Support\Database\Connections;

use PDO;
use PDOException;
use Plugin\Configuration\Config;

class PDOConnection
{
    private static $instance;

    public static function getInstance()
    {

        if (is_null(self::$instance)) {
            try {
                // Database configuration constants from wp-config.php
                $dbHost = defined('DB_HOST') ? DB_HOST : 'localhost';
                $dbPort = defined('DB_PORT') ? DB_PORT : 3306; // Default port
                $dbName = defined('DB_NAME') ? DB_NAME : '';
                $dbUser = defined('DB_USER') ? DB_USER : '';
                $dbPassword = defined('DB_PASSWORD') ? DB_PASSWORD : '';
                $dbCharset = defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4';

                self::$instance = new PDO("mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset={$dbCharset}", $dbUser, $dbPassword);

                // Set statement class attribute if environment is 'local'
                if ('local' == Config::get('environment')) {
                    self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                }

                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            } catch (PDOException $e) {
                exit('DB Error');
            }
        }

        return self::$instance;
    }
}
