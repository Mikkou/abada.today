<?php

namespace app\controllers\admin;

use app\models\admin\Errors;
use fw\core\base\View;

class ErrorsController extends AppController
{
    public static $model;

    public function __construct($route)
    {
        parent::__construct($route);
        self::$model = new Errors();
    }

    public function indexAction ()
    {
        $errors = self::$model->getListErrors();
        $this->set(compact('errors'));
        View::setMeta('Админка :: Ошибки в системе');
    }

    public function cleanAction()
    {
        self::$model->clean();
        redirect();
    }
}