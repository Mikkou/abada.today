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

    public function addAction($data, $langT, $lang)
    {
        View::setMeta('Добавить капоэйриста');
    }

    public function saveAction($data, $langT, $lang)
    {
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

        if (!empty($data)) {

            $data['practiced'] = (isset($data['practiced'])) ? 1 : 0;

            self::$model->load($data);
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