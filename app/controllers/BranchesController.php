<?php

namespace app\controllers;

use app\models\Branches;
use fw\core\base\View;

class BranchesController extends AppController
{
    private static $model;

    public function __construct($route, $params)
    {
        parent::__construct($route, $params);
        self::$model = new Branches();
    }

    public function indexAction($params)
    {
        $langT = $params['langText'];
        $lang = $params['lang'];
        // if need show list of branches
        if (!isset($params['id'])) {
            $branches = self::$model->getAllBranches($lang);
            $title = ($lang === 'en') ? 'Branches' : 'Филиалы';
            View::setMeta($title);
            $this->set(compact('branches', 'langT', 'lang'));
        } else {
            // if click on branch
            $branch = self::$model->getBranch($params['id'], $lang);
            $branch['schedule'] = self::$model->replaceParagraphOnBR($branch['schedule']);
            $this->view = 'card';
            $title = ($lang === 'en') ? 'Branch' : 'Филиал';
            View::setMeta($title);
            $this->set(compact('branch', 'langT', 'lang'));
        }
    }

    public function addAction($data)
    {
        if ((int)$_SESSION['user']['rights'] < 10) redirect();
        $langT = $data['langText'];
        $lang = $data['lang'];
        if (isset($data['country'])) {

            // for checking size of image
            if (!empty($_FILES)) {
                $data['image_size'] = $_FILES['image']['size'];
                self::$model->attributes['image_size'] = '';
            }

            self::$model->load($data);
            if (!self::$model->validate($data, $this->lang, $this->langT)) {
                self::$model->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }

            if (isset(self::$model->attributes['image_size'])) {
                unset(self::$model->attributes['image_size']);
                unset($data['image_size']);
            }

            // if city is new -> saving him and get his id
            if (isset($data['city']) && strpos($data['city'], 'new_') === 0) {
                $data['city'] = self::$model->putNewCity($data['country'], $data['city'], $lang);
            }

            if (!empty($_FILES)) {
                $data['image'] = self::$model->saveImage();
            }
            $data['user_id'] = $_SESSION['user']['id'];
            self::$model->load($data);
            if (self::$model->save(self::$model->table)) {
                $_SESSION['success'] = 'Филиал успешно был добавлен.';
                self::$model->refreshUserSession();
                redirect('/branches');
            } else {
                $_SESSION['error'] = 'Ошибка! Филиал не был добавлен. Обратитесь пожалуйста в техподдержку.';
                redirect('/branches/add');
            }

        }

        $countries = self::$model->getAllCountries($lang);
        $title = ($lang === 'en') ? 'Add branch' : 'Добавить филиал';
        View::setMeta($title);
        $this->set(compact('langT', 'lang', 'countries'));
    }
}
