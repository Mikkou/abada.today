<?php

namespace app\controllers\admin;

use fw\core\base\Controller;

class AppController extends Controller
{
    public $layout = 'admin';

    public function __construct($route)
    {
        parent::__construct($route);
        $this->checkPermission();
    }

    public function checkPermission()
    {
        if (isset($_SESSION['user'])) {
            if (!((int)$_SESSION['user']['rights'] >= 50)) {
                redirect();
            }
        } else {
            redirect('/user/login');
        }
    }
}