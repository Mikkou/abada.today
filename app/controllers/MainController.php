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

    public function __construct($route)
    {
        parent::__construct($route);
        self::$model = new Main;
    }

    public function indexAction($data, $langT, $lang)
    {
        $this->set(compact('langT', 'lang'));
        View::setMeta($langT['home']);
    }

    public function aboutAction($data, $langT, $lang)
    {
        $this->set(compact('langT', 'lang'));
        View::setMeta($langT['about']);
    }
}