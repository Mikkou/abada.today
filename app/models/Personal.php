<?php

namespace app\models;

use fw\core\base\Model;

class Personal extends Model
{
    public $attributes;

    public $rules;

    public function getEvents($lang)
    {
        $id = $_SESSION['user']['id'];
        $result = $this->query("SELECT e.id, e.name, e.begin_date, e.end_date, e.organizer, e.street, e.house, e.block,
                                        e.guest, e.professor, e.image, e.vk, e.category, e.description, e.event_type,
                                        e.user_id, e.coord_x, e.coord_y, ci.{$lang} AS city, co.{$lang} AS country
            FROM events AS e
            LEFT JOIN countries AS co ON e.country = co.id
            LEFT JOIN cities AS ci ON e.city = ci.id
            WHERE user_id = {$id} ORDER BY begin_date");
        return $result;
    }

    public function getBranches($lang)
    {
        $id = $_SESSION['user']['id'];
        $result = $this->query("SELECT b.id, b.street, b.house, b.block, b.image, b.phone,
             b.user_id, b.curator, ci.{$lang} AS city, co.{$lang} AS country
            FROM branches AS b 
            LEFT JOIN countries AS co ON b.country = co.id
            LEFT JOIN cities AS ci ON b.city = ci.id
            WHERE user_id = {$id}");
        return $result;
    }

    public function changePassword($id, $newPass)
    {
        $passHash = password_hash($newPass, PASSWORD_DEFAULT);
        $this->query("UPDATE users SET password = '{$passHash}' WHERE id = '{$id}'");
        return true;
    }

    public function getCitiesByCountry($countryId, $withoutCityId, $lang)
    {
        return $this->query("SELECT id, {$lang} FROM cities 
                  WHERE country_id = {$countryId} AND id != {$withoutCityId}
                  ORDER BY {$lang} ASC");
    }
}