<?php

namespace app\controllers\admin;

use app\models\admin\Main;
use fw\core\base\View;

class MainController extends AppController
{
    public $layout = 'admin';
    public static $model;

    public function __construct($route)
    {
        parent::__construct($route);
        self::$model = new Main();
    }

    public function indexAction($data, $langT, $lang)
    {
        View::setMeta('Админка :: Главная страница');
    }

    public function changeDataAction($post, $langT, $lang)
    {
        $this->view = false;
        return self::$model->changeData($post);
    }

    public function usersAction($data, $langT, $lang)
    {
        $data = self::$model->getUsers();
        View::setMeta('Пользователи');
        $this->set(compact('data'));
    }

    public function citiesAction($data, $langT, $lang)
    {
        $data = self::$model->getAllCities();
        View::setMeta('Список городов');
        $this->set(compact('data'));
    }

    public function countriesAction($data, $langT, $lang)
    {
        $data = self::$model->getAllCountries();
        View::setMeta('Список стран');
        $this->set(compact('data'));
    }
}