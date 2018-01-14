<?php

namespace app\controllers;

use app\models\CapoPeople;
use fw\core\base\View;

class CapoPeopleController extends AppController
{
    public static $model;

    public function __construct($route, $params)
    {
        parent::__construct($route, $params);
        self::$model = new CapoPeople();
    }

    public function indexAction($params)
    {
        $langT = $params['langText'];
        $lang = $params['lang'];
        $people = self::$model->getPeople(1, $lang);
        $this->set(compact('people', 'langT', 'lang'));
        $title = ($lang === 'en') ? 'Сapoeristas' : 'Капоэйристы';
        View::setMeta($title);
    }

    public function rememberAction($params)
    {
        $langT = $params['langText'];
        $lang = $params['lang'];
        $people = self::$model->getPeople(0, $lang);
        $this->set(compact('people', 'langT', 'lang'));
        $title = ($lang === 'en') ? 'Thrown' : 'Бросившие';
        View::setMeta($title);
    }
}