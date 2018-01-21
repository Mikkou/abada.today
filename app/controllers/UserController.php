<?php

namespace app\controllers;

use app\models\User;
use fw\core\base\View;
use PHPMailer\PHPMailer\Exception;

class UserController extends AppController
{
    private static $model;

    public function __construct($route)
    {
        parent::__construct($route);
        self::$model = new User();
    }

    public function singupAction($data, $langT, $lang)
    {
        if (isset($data['password'])) {
            self::$model->load($data);
            if (!self::$model->validate($data, $lang, $langT) || !self::$model->checkUnique()) {
                self::$model->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }
            self::$model->attributes['password'] = password_hash(self::$model->attributes['password'], PASSWORD_DEFAULT);
            if (self::$model->save('users')) {
                $data = self::$model->getUserData($data);
                $data = array_merge(self::$model->getCount($data['id']), $data);
                $_SESSION['success'] = $langT['you_was_successfully_register'];
                $_SESSION['user'] = $data;
            } else {
                $_SESSION['error'] = $langT['error_try_later'];
            }
            redirect('/');
        }
        $this->set(compact('langT', 'lang'));
        View::setMeta($langT['registration']);
    }

    public function loginAction($data, $langT, $lang)
    {
        if (!empty($data)) {
            if (self::$model->login()) {
                redirect('/');
            } else {
                $_SESSION['error'] = $langT['login_password_wrong'];
                redirect('/user/login');
            }
        }
        $this->set(compact('langT', 'lang'));
        View::setMeta($langT['login']);
    }

    public function logoutAction($data, $langT, $lang)
    {
        if (isset($_SESSION['user'])) unset($_SESSION['user']);
        redirect('/');
    }

    public function editAction($data, $langT, $lang)
    {
        $data['id'] = $_SESSION['user']['id'];
        self::$model->query("UPDATE users SET lastname = '{$data['lastname']}', firstname = '{$data['firstname']}',
                    patronymic = '{$data['patronymic']}', phone = '{$data['phone']}' WHERE id = {$data['id']}");
        redirect('/personal');
    }

    public function restorePasswordAction($data, $langT, $lang)
    {
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

            if (!self::$model->validate($data, $lang, $langT)) {
                self::$model->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }
            $login = $data['login'];
            if (self::$model->restorePassword($login, $langT)) {
                $_SESSION['success'] = $langT['mail_new_password'];
            } else {
                throw new \Exception("Новый пароль не был отослан на почту {$login}");
            }
            redirect('/user/login');
        }

        $this->set(compact('langT', 'lang'));
        View::setMeta($langT['restore_password']);
    }
}