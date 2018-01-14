<?php

namespace app\controllers;

use app\models\Main;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use fw\core\App;
use \R;
use fw\core\base\View;

class MainController extends AppController
{
    private static $model;

    public function __construct($route, $params)
    {
        parent::__construct($route, $params);
        self::$model = new Main;
    }

    public function indexAction($params)
    {
        $langT = $params['langText'];
        $lang = $params['lang'];
        $title = ($lang === 'ru') ? 'Главная' : 'Home';
        $this->set(compact('langT', 'lang'));
        View::setMeta($title);
    }

    public function aboutAction($params)
    {
        $langT = $params['langText'];
        $lang = $params['lang'];
        $title = ($lang === 'en') ? 'About' : 'О проекте';
        $this->set(compact('langT', 'lang'));
        View::setMeta($title);
    }
}