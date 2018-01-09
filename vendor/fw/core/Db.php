<?php

namespace fw\core;

use R;

class Db
{
//    use TSingleton;

    protected $pdo;
//    protected static $instance;
    public static $countSql = 0;
    public static $queries = [];

    protected static $instance;

    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    protected function __construct()
    {
        $db = require ROOT . '/config/config_db.php';
        require LIBS . '/rb.php';
        R::setup($db['dsn'], $db['user'], $db['pass']);
        R::freeze(true);
        R::fancyDebug( TRUE );
    }

    public function query($sql, $params = [])
    {
        return R::getAll($sql, $params);
    }
}