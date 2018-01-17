<?php

namespace app\controllers;

use app\models\Branches;
use fw\core\base\View;

class BranchesController extends AppController
{
    private static $model;

    public function __construct($route)
    {
        parent::__construct($route);
        self::$model = new Branches();
    }

    public function indexAction($data, $langT, $lang)
    {
        if (!isset($data['id'])) {
            $branches = self::$model->getAllBranches($lang);
            View::setMeta($langT['branches']);
            $this->set(compact('branches', 'langT', 'lang'));
        } else {
            // if click on branch -> open card
            $branch = self::$model->getBranch($data['id'], $lang);
            $branch['schedule'] = self::$model->replaceParagraphOnBR($branch['schedule']);
            $this->view = 'card';
            View::setMeta($langT['branch']);
            $this->set(compact('branch', 'langT', 'lang'));
        }
    }

    public function addAction($data, $langT, $lang)
    {
        if ((int)$_SESSION['user']['rights'] < 10) redirect();
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
                $_SESSION['success'] = $langT['branch_was_successfully_add'];
                self::$model->refreshUserSession();
                redirect('/branches');
            } else {
                $_SESSION['error'] = $langT['error_branch_not_add'];
                redirect('/branches/add');
            }

        }

        $countries = self::$model->getAllCountries($lang);
        View::setMeta($langT['add_branch']);
        $this->set(compact('langT', 'lang', 'countries'));
    }
}
