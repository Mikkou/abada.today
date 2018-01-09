<?php

namespace parser;

use DbSimple_Generic;

class DBHelper
{
    protected static $dbInstance;

    protected static $table;

    public static function getInstance($table)
    {
        self::$table = $table;

        if (self::$dbInstance === null) {

            self::$dbInstance = DbSimple_Generic::connect('mysql://' . DB_USER . ':' . DB_PASS . '@localhost/' . DB_NAME . '');

        }

        return self::$dbInstance;
    }
}

