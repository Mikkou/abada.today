<?php

namespace app\models;

use fw\core\base\Model;

class CapoPeople extends Model
{
    public function getPeople($type, $lang)
    {
        $people = $this->query("SELECT cp.apelido, cp.name, cp.gender, cp.last_corda_year,
                cp.curator, cp.vk, cp.image, ci.{$lang} AS city, co.{$lang} AS country, g.status, g.corda
            FROM `capo_people` AS cp
            LEFT JOIN gradation AS g ON cp.corda = g.id 
            LEFT JOIN cities AS ci ON cp.city = ci.id 
            LEFT JOIN countries AS co ON cp.country = co.id 
            WHERE practiced = {$type}
        ");
        return $people;
    }
}