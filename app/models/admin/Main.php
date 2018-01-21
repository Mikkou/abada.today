<?php

namespace app\models\admin;

use fw\core\base\Model;

class Main extends Model
{
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