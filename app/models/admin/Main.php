<?php

namespace app\models\admin;

use fw\core\base\Model;

class Main extends Model
{
    public function login()
    {
        $login = !empty(trim($_POST['login'])) ? trim($_POST['login']) : null;
        $password = !empty(trim($_POST['password'])) ? trim($_POST['password']) : null;
        if ($login && $password) {
            if ($login === 'Mikkou' && $password === 'mrmikkou1887555') {
                $_SESSION['admin'] = true;
                return true;
            }
        }
        return false;
    }

    /**
     * @param $post - date from ajax
     * @return bool
     */
    public function changeData($post)
    {
        $this->query("UPDATE {$post['table']} SET {$post['column']} = '{$post['str']}' WHERE id = {$post['id']}");
        return true;
    }

    public function getUsers()
    {
        $data = $this->query("SELECT * FROM users");
        return $data;
    }

    /**
     * @return array - all cities
     */
    public function getAllCities()
    {
        $this->table = 'cities';
        return $this->findAll();
    }
}