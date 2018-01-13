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

    public function indexAction()
    {
        // check auth
        if (!isset($_SESSION['user'])) redirect('/user/login');
        if (isset($_SESSION['admin'])) {
            View::setMeta('Админка :: Главная страница');
        } else {
            View::setMeta('Админка :: Авторизация');
            redirect('/admin/main/login');
        }
    }

    public function loginAction()
    {
        if (!empty($_POST)) {
            if (self::$model->login()) {
                $_SESSION['success'] = 'Вы успешно авторизованы.';
            } else {
                $_SESSION['error'] = 'Логин/пароль введены неверно.';
            }
            redirect('/admin');
        }
        View::setMeta('Вход');
    }

    public function logoutAction()
    {
        if (isset($_SESSION['admin'])) unset($_SESSION['admin']);
        redirect('/');
    }

    public function eventsAction()
    {
        if (!isset($_SESSION['user'])) redirect('/main/login');
        $data = self::$model->getEventsData();
        View::setMeta('События');
        $this->set(compact('data'));
    }

    public function changeDataAction($post)
    {
        $this->view = false;
        return self::$model->changeData($post);
    }

    public function usersAction()
    {
        if (!isset($_SESSION['user'])) redirect('/main/login');
        $data = self::$model->getUsers();
        View::setMeta('Пользователи');
        $this->set(compact('data'));
    }

    public function citiesAction()
    {
        $data = self::$model->getAllCities();
        View::setMeta('Список городов');
        $this->set(compact('data'));
    }

    public function countriesAction()
    {
        $data = self::$model->getAllCountries();
        View::setMeta('Список стран');
        $this->set(compact('data'));
    }
}