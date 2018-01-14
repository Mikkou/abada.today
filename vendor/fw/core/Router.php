<?php

namespace fw\core;

class Router
{
    /**
     * Таблица маршрутов
     * @var array
     */
    protected static $routes = [];

    /**
     * Текущий маршрут
     * @var array
     */
    protected static $route = [];

    protected static $registry;

    /**
     * Добалвяет маршрут в таблицу маршрутов
     *
     * @param string $regexp регулярное выражение маршрута
     * @param array $route маршрут ([controller, action, params])
     */
    public static function add($regexp, $route = [])
    {
        self::$routes[$regexp] = $route;
    }

    public static function getRoutes()
    {
        return self::$routes;
    }

    public static function getRoute()
    {
        return self::$route;
    }

    /**
     * ищет URL в таблице маршрутов
     * @param string $url входящий URL
     * @return boolean
     */
    public static function matchRoute($url)
    {
        foreach (self::getRoutes() as $pattern => $route) {
            if (preg_match("#$pattern#i", $url, $matches)) {
                foreach ($matches as $k => $v) {
                    if (is_string($k)) {
                        $route[$k] = $v;
                    }
                }
                if (!isset($route['action'])) {
                    $route['action'] = 'index';
                }
                // prefix for admin controllers
                if (!isset($route['prefix'])) {
                    $route['prefix'] = '';
                } else {
                    $route['prefix'] .= '\\';
                }
                $route["controller"] = upperCamelCase($route['controller']);
                self::$route = $route;
                return true;
            }
        }
        return false;
    }

    /**
     * перенаправляет URL по корректному маршруту1
     * @param string $url входящий URL
     * @throws \Exception
     */
    public static function dispatch($url)
    {
        self::$registry = Registry::instance();
        $params = self::getParams($url);
        $params['langText'] = self::getLangText($params);
        $url = self::removeQueryString($url);
        if (self::matchRoute($url)) {
            $controller = 'app\controllers\\' . self::$route['prefix'] . upperCamelCase(self::$route['controller'])
                . 'Controller';
            if (class_exists($controller)) {

                $cObj = new $controller(self::$route, $params);
                $action = lowerCamelCase(self::$route['action']) . 'Action';
                if (method_exists($cObj, $action)) {
                    $cObj->$action($params);
                    $cObj->getView();
                } else {
                    throw new \Exception("Метод <b>$controller::$action</b> не найден", 404);
                }
            } else {
                throw new \Exception("Контроллер <b>$controller</b> не найден", 404);
            }
        } else {
            throw new \Exception("Страница {$url} не найдена", 404);

        }
    }

    protected static function getParams($url)
    {
        if ($_POST) {
            $params = (is_string($_POST)) ? json_decode($_POST) : $_POST;
        } else {
            $params = [];
            if (strpos($url, '&') !== false) {
                $arrayPartsUrl = explode('&', $url);
                unset($arrayPartsUrl[0]);
                foreach ($arrayPartsUrl as $v) {
                    $array = explode('=', $v);
                    $params[$array[0]] = $array[1];
                }
            } elseif (strpos($url, '=') !== false) {
                $array = explode('=', $url);
                $params[$array[0]] = $array[1];
            }
        }

        $params = self::checkLang($params);

        return $params;
    }

    protected static function checkLang($params)
    {
        if (isset($params['lang'])) {
            if (isset($_COOKIE['lang'])) {
                if ($params['lang'] !== $_COOKIE['lang']) {
                    setCookie('lang', null, 0, '/');
                    setCookie('lang', $params['lang']);
                }
            } else {
                setCookie('lang', $params['lang']);
            }

        } else {

            $langs = [
                'ru' => ['ru', 'be', 'uk', 'ky', 'ab', 'mo', 'et', 'lv']
            ];

            // add detect language - default is en
            $params['lang'] = self::$registry->langDetect->getBestMatch('en', $langs);

            // check getting lang on existing
            if ($params['lang'] !== 'en' && $params['lang'] !== 'ru') {
                throw new \Exception("Язык '{$params['lang']}' не существует на сайте.");
            }

            if (isset($_COOKIE['lang'])) {
                $params['lang'] = $_COOKIE['lang'];
            } else {
                setCookie('lang', $params['lang']);
            }

        }

        return $params;
    }

    protected static function getLangText($params)
    {
        $langText = require ROOT . "/public/lang/{$params['lang']}.php";
        return $langText;
    }

    protected static function removeQueryString($url)
    {
        if ($url) {
            $params = explode('&', $url, 2);
            if (false === strpos($params[0], '=')) {
                return trim($params[0], '/');
            } else {
               return '';
            }
        }
        return $url;
    }
}