<?php

namespace app\controllers;

use app\models\Main;
use fw\core\base\Controller;

class AppController extends Controller
{
    public $menu;
    public $meta = [];

    public function __construct($route)
    {
        parent::__construct($route);
    }

    protected function setMeta($title = '', $desc = '', $keywords = '')
    {
        $this->meta['title'] = $title;
        $this->meta['desc'] = $desc;
        $this->meta['keywords'] = $keywords;
    }

    protected function checkPermission($user = 'usual')
    {
        // check permission for trainer
        if ($user === 'trainer') {

            if (isset($_SESSION['user'])) {
                if (!((int)$_SESSION['user']['rights'] >= 10)) {
                    redirect();
                }
            } else {
                redirect('/user/login');
            }

            // check permission for usual users
        } else {

            if (!isset($_SESSION['user'])) {
                redirect('/user/login');
            }

        }
    }
}