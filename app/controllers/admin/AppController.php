<?php

namespace app\controllers\admin;

use fw\core\base\Controller;

class AppController extends Controller
{
    public $layout = 'admin';

    public function __construct($route, $params)
    {
        parent::__construct($route, $params);
    }
}