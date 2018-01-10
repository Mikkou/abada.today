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

    public function indexAction()
    {
        if (!isset($_SESSION['user'])) redirect('/main/login');
        $data = self::$model->getAllBranches('ru');
        View::setMeta('Админка :: Филиалы');
        $this->set(compact('data', 'address'));
    }

    public function editAction($params)
    {
        if (!isset($_SESSION['user'])) redirect('/main/login');
        if (empty($params) || (int)$params['id'] < 1) redirect();
        if (!isset($_SESSION['admin'])) redirect('/');
        $branch = self::$model->getBranch($params['id']);
        View::setMeta('Админка :: Редактировать филиал');
        $this->set(compact('branch'));
    }

    public function saveBranchAction($params)
    {
        if (!isset($_SESSION['user'])) redirect('/main/login');
        $data = $params;
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

        self::$model->rules = [
            'required' => [
                ['country'],
                ['city'],
                ['street'],
                ['house'],
                ['phone'],
            ],
            'url' => [
                ['link'],
//                ['site']
            ],
            'max' => [
                ['image_size', 500000]
            ]
        ];

        // for checking size of image
        if (!empty($_FILES)) {
            $data['image_size'] = $_FILES['image']['size'];
            self::$model->attributes['image_size'] = '';
        }

        self::$model->load($data);

        if (!self::$model->validate($data)) {
            self::$model->getErrors();
            $_SESSION['form_data'] = $data;
            redirect();
        }

        if (isset(self::$model->attributes['image_size'])) {
            unset(self::$model->attributes['image_size']);
            unset($data['image_size']);
        }

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

    public function deleteAction($params)
    {
        if (!isset($_SESSION['user'])) redirect('/main/login');
        if (empty($params) || (int)$params['id'] < 1) redirect();
        self::$model->deleteObj($params['id'], 'branches');
        $_SESSION['success'] = 'Филиал был успешно удален.';
        $this->view = false;
        $_SESSION['user']['c_own_branches'] -= 1;
        redirect('/admin/branches');
    }

    public function addAction($data)
    {
        if (!isset($_SESSION['user'])) redirect('/main/login');
        if ((int)$_SESSION['user']['rights'] < 49) redirect();
        if (isset($data['country'])) {

            // for checking size of image
            if (!empty($_FILES)) {
                $data['image_size'] = $_FILES['image']['size'];
                self::$model->attributes['image_size'] = '';
            }

            self::$model->load($data);
            if (!self::$model->validate($data)) {
                self::$model->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }

            if (isset(self::$model->attributes['image_size'])) {
                unset(self::$model->attributes['image_size']);
                unset($data['image_size']);
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
        View::setMeta('Админка :: Добавление филиала');
    }
}