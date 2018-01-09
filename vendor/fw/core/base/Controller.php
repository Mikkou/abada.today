<?php

namespace fw\core\base;

use fw\core\Registry;

abstract class Controller
{
    public $route = [];
    public $view;

    /**
     * текущий шаблон
     * @var string
     */
    public $layout;

    /**
     * пользовательские данные
     * @var array
     */
    public $vars = [];

    public $registry;

    public function __construct($route)
    {
        $this->route = $route;
        $this->view = $route['action'];
        $this->registry = Registry::instance();
    }

    public function getView()
    {
        if ($this->view) {
            $vObj = new View($this->route, $this->layout, $this->view);
            $vObj->render($this->vars);
        }
    }

    public function set($vars)
    {
        $this->vars = $vars;
    }

    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    public function loadView($view, $vars = [])
    {
        extract($vars);
        require APP . "/views/{$this->route['controller']}/{$view}.php";
    }
}