<?php

namespace App\Core;

Use PDO;

class Db {

    private static $_db = null;

    public static function getInstance() {

        if ( self::$_db === null ) {

            $config = config('db');

            self::$_db = new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'] . ';charset=' . $config['charset'], $config['user'], $config['password'], $config['opt']);
        }

        return self::$_db;
    }
    
    private function __construct() {
    }
    private function __clone() {
    }
    private function __wakeup() {
    }
}