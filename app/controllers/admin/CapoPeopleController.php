<?php

namespace app\controllers\admin;

use app\models\admin\CapoPeople;
use fw\core\base\View;

class CapoPeopleController extends AppController
{
    private static $model;

    public function __construct($route)
    {
        parent::__construct($route);
        self::$model = new CapoPeople();
    }

    public function addAction($params)
    {
        View::setMeta('Добавить капоэйриста');
    }

    public function saveAction($params)
    {
        if (!isset($_SESSION['user'])) redirect('/user/login');
        if ((int)$_SESSION['user']['rights'] < 10) redirect();

        self::$model->attributes = [
            'apelido' => '',
            'name' => '',
            'country' => '',
            'city' => '',
            'last_corda_year' => '',
            'curator' => '',
            'vk' => '',
            'image' => '',
            'corda' => '',
            'practiced' => '',
            'gender' => '',
        ];

        if (!empty($params)) {

            if (isset($params['practiced'])) {
                $params['practiced'] = 1;
            } else {
                $params['practiced'] = 0;
            }

            self::$model->load($params);
            if (self::$model->save('capo_people')) {
                $_SESSION['success'] = 'Капоэйрист успешно был добавлен.';
                redirect('/admin/capo-people/add');
            } else {
                $_SESSION['error'] = 'Ошибка! Капоэйрист не был добавлен.';
                redirect('/admin/capo-people/add');
            }
        }
        View::setMeta('Админ :: Добавление капоэйриста');
    }
}