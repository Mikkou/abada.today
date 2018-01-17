<?php

namespace app\controllers;

use app\models\CapoPeople;
use fw\core\base\View;

class CapoPeopleController extends AppController
{
    public static $model;

    public function __construct($route)
    {
        parent::__construct($route);
        self::$model = new CapoPeople();
    }

    public function indexAction($data, $langT, $lang)
    {
        $people = self::$model->getPeople(1, $lang);
        $this->set(compact('people', 'langT', 'lang'));
        View::setMeta($langT['capoeristas']);
    }

    public function rememberAction($data, $langT, $lang)
    {
        $people = self::$model->getPeople(0, $lang);
        $this->set(compact('people', 'langT', 'lang'));
        View::setMeta($langT['thrown']);
    }
}