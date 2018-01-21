<?php

namespace app\controllers\admin;

use app\models\admin\Branches;
use fw\core\base\View;

class BranchesController extends AppController
{
    public $layout = 'admin';
    public static $model;

    public function __construct($route)
    {
        parent::__construct($route);
        self::$model = new Branches();
    }

    public function indexAction($data, $langT, $lang)
    {
        $data = self::$model->getAllBranches('ru');
        View::setMeta('Админка :: Филиалы');
        $this->set(compact('data', 'address'));
    }

    public function editAction($data, $langT, $lang)
    {
        $branch = self::$model->getBranch($data['id'], 'ru');
        $countries = self::$model->getAllCountries('ru', $branch['country_id']);
        $countrysCities = self::$model->getCitiesByCountry($branch['country_id'], $branch['city_id'], 'ru');

        View::setMeta('Админка :: Редактировать филиал');
        $this->set(compact('branch', 'countries', 'countrysCities'));
    }

    public function saveBranchAction($data, $langT, $lang)
    {
        self::$model->attributes = [
            'country' => '',
            'city' => '',
            'street' => '',
            'house' => '',
            'block' => '',
            'curator' => '',
            'image' => '',
            'phone' => '',
            'link' => '',
            'age_groups' => '',
            'site' => '',
            'schedule' => '',
            'id' => '',
        ];

        $data = self::$model->preparationValidationImage($data, $lang, $langT);

        // if city is new -> saving him and get his id
        if (isset($data['city']) && strpos($data['city'], 'new_') === 0) {
            $data['city'] = self::$model->putNewCity($data['country'], $data['city'], 'ru');
        }

        // unite string sql request
        $str = "country = '{$data['country']}', city = '{$data['city']}', street = '{$data['street']}',
         house = '{$data['house']}', block = '{$data['block']}', phone = '{$data['phone']}', link = '{$data['link']}',
          age_groups = '{$data['age_groups']}', site = '{$data['site']}', schedule = '{$data['schedule']}',
           curator = '{$data['curator']}'";

        if (!empty($_FILES['image']['tmp_name'])) {
            $data['image'] = self::$model->saveImage(true, $data['id'], 'branches');
            $str .= ", image = '{$data['image']}'";
        }

        if (self::$model->update('branches', $str, $data['id'])) {
            $_SESSION['success'] = 'Данные сохранены.';
            self::$model->refreshUserSession();
        } else {
            $_SESSION['success'] = 'Ошибка! Данные не были сохранены.';
        }
        redirect('/admin/branches');

    }

    public function deleteAction($data, $langT, $lang)
    {
        self::$model->deleteObj($data['id'], 'branches');
        $_SESSION['success'] = 'Филиал был успешно удален.';
        $this->view = false;
        $_SESSION['user']['c_own_branches'] -= 1;
        redirect('/admin/branches');
    }

    public function addAction($data, $langT, $lang)
    {
        if (isset($data['country'])) {

            self::$model->attributes = [
                'country' => '',
                'city' => '',
                'street' => '',
                'house' => '',
                'block' => '',
                'curator' => '',
                'image' => '',
                'phone' => '',
                'link' => '',
                'age_groups' => '',
                'site' => '',
                'schedule' => '',
                'user_id' => '',
            ];

            $data = self::$model->preparationValidationImage($data, $lang, $langT);

            // if city is new -> saving him and get his id
            if (isset($data['city']) && strpos($data['city'], 'new_') === 0) {
                $data['city'] = self::$model->putNewCity($data['country'], $data['city'], 'ru');
            }

            if (!empty($_FILES)) {
                $data['image'] = self::$model->saveImage();
            }
            $data['user_id'] = 0;
            self::$model->load($data);
            if (self::$model->save(self::$model->table)) {
                $_SESSION['success'] = 'Филиал успешно был добавлен.';
                self::$model->refreshUserSession();
                redirect('/admin/branches');
            } else {
                $_SESSION['error'] = 'Ошибка! Филиал не был добавлен.';
                redirect('/admin/branches/add');
            }

        }
        $countries = self::$model->getAllCountries();
        $this->set(compact('countries'));
        View::setMeta('Админка :: Добавление филиала');
    }
}