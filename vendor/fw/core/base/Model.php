<?php

namespace fw\core\base;

use fw\core\Db;
use Valitron\Validator;

abstract class Model
{
    protected $pdo;
    protected $table;
    protected $pk = 'id';
    public $attributes = [];
    public $errors = [];
    public $rules = [];
    public $pathToUploads;

    public function __construct()
    {
        $this->pdo = Db::instance();
    }

    public function load($data)
    {
        foreach ($this->attributes as $name => $value) {
            if (isset($data[$name])) {
                $this->attributes[$name] = $data[$name];
            }
        }
    }

    public function validate($data)
    {
        Validator::langDir(WWW . '/valitron/lang');
        Validator::lang('ru');
        $v = new Validator($data);
        // new rule
        $v->addRule('withoutNumbers', function ($field, $value, array $params, array $fields) {
            $output = preg_replace('/[^0-9]/', '', $value);
            if (empty($output)) {
                return true;
            }
            return false;
        }, 'не должен содержать цифр.');
        $v->addRule('greaterThen', function ($field, $value, array $params, array $fields) {
            if ($value < $fields['end_date'] || $value === $fields['end_date']) {
                return true;
            }
            return false;
        }, 'дата начала должна быть меньше даты окончания.');
        $v->rules($this->rules);
        if ($v->validate()) {
            return true;
        }
        $this->errors = $v->errors();
        return false;
    }

    public function getErrors()
    {
        $errors = "<ul>";
        foreach ($this->errors as $error) {
            foreach ($error as $item) {
                $errors .= "<li>{$item}</li>";
            }
        }
        $errors .= "</ul>";
        $_SESSION['error'] = $errors;
    }

    public function save($table)
    {
        $tbl = \R::dispense($table);
        foreach ($this->attributes as $name => $value) {
            $tbl->$name = $value;
        }
        return \R::store($tbl);
    }

    public function query($sql, $params = [])
    {
        return $this->pdo->query($sql, $params);
    }

    public function findAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->pdo->query($sql);
    }

    public function findOne($id, $field = '')
    {
        $field = $field ?: $this->pk;
        $sql = "SELECT * FROM {$this->table} WHERE $field = ? LIMIT 1";
        return $this->pdo->query($sql, [$id]);
    }

    public function findBySql($sql, $params = [])
    {
        return $this->pdo->query($sql, $params);
    }

    public function findLike($str, $field, $table = '')
    {
        $table = $table ?: $this->table;
        $sql = "SELECT * FROM $table WHERE $field LIKE ?";
        return $this->pdo->query($sql, ['%' . $str . '%']);
    }


    /**
     * this function have 2 functions:
     * 1. save image
     * 2. save image after delete old
     * @param bool $deleteOld - (true) if need at first delete old image and after load new
     * @param string $id - of object
     * @param string $table - where need delete
     * @return string - path to new image
     */
    public function saveImage($deleteOld = false, $id = '', $table = '')
    {
        $this->pathToUploads = ($this->table) ? WWW . '/uploads/' . $this->table : WWW . '/uploads';
        if ($deleteOld) $this->deleteImage($id, $table);
        $uploadFile = '';
        foreach ($_FILES as $key => $value) {
            if ($value['error'] == 0) {
                $partsName = explode('.', $_FILES[$key]['name']);
                $uploadFile = $this->pathToUploads . '/' . $partsName[0] . '_' . time() . '.' . $partsName[1];
                move_uploaded_file($_FILES[$key]['tmp_name'], $uploadFile);
                $uploadFile = 'http://' . $_SERVER['SERVER_NAME'] . '/public/' . explode('public/', $uploadFile)[1];
            }
        }
        return $uploadFile;
    }

    public function getCount($id)
    {
        $cOwnEvents = $this->query("SELECT COUNT(*) as c_own_events FROM events WHERE user_id = {$id}")[0];
        $cOwnBranches = $this->query("SELECT COUNT(*) as c_own_branches FROM branches WHERE user_id = {$id}")[0];
        $cBranches = $this->query("SELECT COUNT(*) AS c_branches FROM branches")[0];
        $cEvents = $this->query("SELECT COUNT(*) AS c_events FROM events WHERE begin_date > NOW()")[0];
        $result = array_merge($cOwnEvents, $cOwnBranches, $cBranches, $cEvents);
        return $result;
    }

    /**
     * delete event on his id
     * @param $id
     * @param $table - table in db where need delete object
     * @return bool
     */
    public function deleteObj($id, $table)
    {
        $this->table = $table;
        $this->deleteImage($id, $table);
        $this->query("DELETE FROM `{$table}` WHERE id = {$id}");
        return true;
    }

    /**
     * refresh session users data
     * @return bool
     */
    public function refreshUserSession()
    {
        $id = $_SESSION['user']['id'];
        $user = $this->query("SELECT * FROM users WHERE id = {$id}")[0];
        $data = array_merge($this->getCount($id), $user);
        unset($_SESSION['user']);
        $_SESSION['user'] = $data;
        return true;
    }

    public function update($table, $str, $id)
    {
        $this->query("UPDATE {$table} SET {$str} WHERE id = {$id}");
        return true;
    }

    public function deleteImage($id, $table)
    {
        $image = $this->query("SELECT image FROM `{$table}` WHERE id = {$id} ");
        // if have image
        if (!empty($image)) {
            $image = $image[0]['image'];
            if (file_exists($image) && $image !== $_SERVER['DOCUMENT_ROOT']) unlink($image);
            return true;
        }
        return false;
    }

    public function getEventById($id, $lang)
    {
        $data = $this->query("SELECT e.name, e.begin_date, e.end_date, e.organizer, e.street, e.house, e.block,
                                        e.guest, e.professor, e.image, e.vk, e.category, e.description, e.event_type,
                                        e.user_id, e.coord_x, e.coord_y, co.{$lang} AS country, ci.{$lang} AS city,
                                        concat(co.{$lang}, ', ', ci.{$lang}, ', ', e.street, ', ', e.house,
                                         '/', e.block) as address
            
            FROM events as e 
            LEFT JOIN countries AS co ON e.country = co.id
            LEFT JOIN cities AS ci ON e.city = ci.id
            WHERE e.id = {$id}");
        if ($data) {
            return $data[0];
        } else {
            return [];
        }
    }

    public function replaceParagraphOnBR($desc)
    {
        return str_replace("\r\n", "<br>", $desc);
    }

    public function getEventsCategories():array
    {
      return $this->query("SELECT * FROM events_categories");
    }

    public function getAllBranches($lang):array
    {
        $data = $this->query("SELECT b.id, b.house, b.block, b.image, b.phone,
            u.nickname, u.lastname, u.firstname, b.user_id, b.curator,
            concat(co.{$lang}, ', ', ci.{$lang}, ', ', b.street, ', ', b.house, '/', b.block) as address
            
            FROM branches as b 
            INNER JOIN users as u ON b.user_id = u.id
            LEFT JOIN countries AS co ON b.country = co.id
            LEFT JOIN cities AS ci ON b.city = ci.id");
        return $data;
    }

    public function getAllCountries($lang = 'en, ru'):array
    {
        $data = $this->query("SELECT id, {$lang} FROM countries ORDER BY {$lang}");
        return $data;
    }

    public function getBranch($id)
    {
        $this->table = 'branches';
        $data = $this->findOne($id)[0];
        return $data;
    }
}
