<?php

namespace app\controllers;

use app\models\User;
use fw\core\base\View;
use PHPMailer\PHPMailer\Exception;

class UserController extends AppController
{
    private static $model;

    public function __construct($route, $params)
    {
        parent::__construct($route, $params);
        self::$model = new User();
    }

    public function singupAction($params)
    {
        if (!empty($_POST)) {
            $data = $_POST;
            self::$model->load($data);
            if (!self::$model->validate($data, $this->lang, $this->langT) || !self::$model->checkUnique()) {
                self::$model->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }
            self::$model->attributes['password'] = password_hash(self::$model->attributes['password'], PASSWORD_DEFAULT);
            if (self::$model->save('users')) {
                $data = self::$model->query("SELECT * FROM users WHERE email = '{$data['email']}'")[0];
                $data = array_merge(self::$model->getCount($data['id']), $data);
                $_SESSION['success'] = 'Вы успешно зарегистрировались.';
                $_SESSION['user'] = $data;
            } else {
                $_SESSION['error'] = 'Ошибка! Попробуйте позже.';
            }
            redirect('/');
        }
        $langT = $params['langText'];
        $lang = $params['lang'];
        $this->set(compact('langT', 'lang'));
        $title = ($lang === 'en') ? 'Registration' : 'Регистрация';
        View::setMeta($title);
    }

    public function loginAction($params)
    {
        if (!empty($_POST)) {
            if (self::$model->login()) {
                redirect('/');
            } else {
                $_SESSION['error'] = 'Логин/пароль введены неверно.';
                View::setMeta('Вход');
                redirect('/main/login');
            }
        }
        $langT = $params['langText'];
        $lang = $params['lang'];
        $this->set(compact('langT', 'lang'));
        $title = ($lang === 'en') ? 'Login' : 'Вход';
        View::setMeta($title);
    }

    public function logoutAction()
    {
        if (isset($_SESSION['user'])) unset($_SESSION['user']);
        redirect('/');
    }

    public function editAction($data)
    {
        $data['id'] = $_SESSION['user']['id'];
        self::$model->query("UPDATE users SET lastname = '{$data['lastname']}', firstname = '{$data['firstname']}',
                    patronymic = '{$data['patronymic']}', phone = '{$data['phone']}' WHERE id = {$data['id']}");
        redirect('/personal');
    }

    public function restorePasswordAction($data)
    {
        $langT = $data['langText'];
        $lang = $data['lang'];

        if (isset($data['login'])) {

            self::$model->attributes = [
                'login'
            ];
            self::$model->rules = [
                'email' => [
                    ['login']
                ]
            ];

            self::$model->load($data);

            if (!self::$model->validate($data, $this->lang, $this->langT)) {
                self::$model->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }
            $login = $data['login'];
            if (self::$model->restorePassword($login, $langT)) {
                $_SESSION['success'] = 'Вам на указанную почту был выслан новый пароль.';
            } else {
                throw new \Exception("Новый пароль не был отослан на почту {$login}");
            }
            redirect('/user/login');
        }

        $this->set(compact('langT', 'lang'));
        $title = ($lang === 'en') ? 'Restore password' : 'Восстановление пароля';
        View::setMeta($title);
    }
}