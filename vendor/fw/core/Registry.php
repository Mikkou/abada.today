<?php

namespace fw\core;

class Registry
{
//    use TSingleton;

    public static $objects = [];
    protected static $instance;
    protected function __construct()
    {
        $config = include_once ROOT . '/config/config.php';
        foreach ($config['components'] as $name => $component){
            self::$objects[$name] = new $component;
        }
    }

    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new Registry();
        }
        return self::$instance;
    }

    public function __get($name)
    {
        if (is_object(self::$objects[$name])) {
            return self::$objects[$name];
        }
    }

    public function __set($name, $object)
    {
        if (!isset(self::$objects[$name])) {
            self::$objects[$name] = new $object;
        }
    }
}