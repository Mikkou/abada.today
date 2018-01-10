<?php

namespace app\models;

use fw\core\base\Model;
use PHPMailer\PHPMailer\PHPMailer;

class User extends Model
{
    public $attributes = [
        'password' => '',
        'email' => '',
    ];

    public $rules = [
        'required' => [
            ['password'],
            ['email'],
        ],
        'email' => [
            ['email'],
        ],
        'lengthMin' => [
            ['password', 6],
        ]
    ];

    public function checkUnique()
    {
        $user = \R::findOne('users', 'email = ? LIMIT 1', [$this->attributes['email']]);
        if ($user) {
            if ($user->email == $this->attributes['email']) {
                $this->errors['unique'][] = 'Этот email уже занят.';
            }
            return false;
        }
        return true;
    }

    public function login()
    {
        $login = !empty(trim($_POST['login'])) ? trim($_POST['login']) : null;
        $password = !empty(trim($_POST['password'])) ? trim($_POST['password']) : null;
        if ($login && $password) {
            $user = $this->query("SELECT * FROM users WHERE email = '{$login}' LIMIT 1");
            if ($user) {
                if (password_verify($password, $user[0]['password'])) {
                    $user = array_merge($this->getCount($user[0]['id']), $user[0]);
                    foreach ($user as $k => $v) {
                        if ($k != 'password') $_SESSION['user'][$k] = $v;
                    }
                    return true;
                }
            }
            return false;
        }
        return false;
    }

    public function restorePassword($login)
    {

        $newPass = rand(600000, 1000000);

        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
//        $mail->Host = "smtp.abada.today";
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "ssl";
        $mail->Port = 465;
        $mail->CharSet = "UTF-8";

        $mail->Username = 'capoworld.net@gmail.com';
//        $mail->Username = 'support@abada.today';
        $mail->Password = 'mrmikkou1887342';
//        $mail->Password = 'mK5cXI8AMM';
//        $mail->setFrom('support@abada.today', 'Техподдержка');
        $mail->setFrom('capoworld.net@gmail.com', 'Техподдержка');
        $mail->addAddress($login, '');
        $mail->Subject  = 'Новый пароль';
        $mail->Body     = 'Ваш новый пароль - ' . $newPass;

        if(!$mail->send()) {
            return false;
        } else {
            $passHash = password_hash($newPass, PASSWORD_DEFAULT);
            $this->query("UPDATE users SET password = '{$passHash}' WHERE email = '{$login}'");
            return true;
        }
    }
}