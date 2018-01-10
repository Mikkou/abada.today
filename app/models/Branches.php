<?php

namespace app\models;

use fw\core\base\Model;

class Branches extends Model
{
    public $table = 'branches';

    public $attributes = [
        'country' => '',
        'city' => '',
        'street' => '',
        'house' => '',
        'block' => '',
        'image' => '',
        'phone' => '',
        'link' => '',
        'age_groups' => '',
        'site' => '',
        'schedule' => '',
        'user_id' => '',
    ];

    public $rules = [
        'required' => [
            ['country'],
            ['city'],
            ['street'],
            ['house'],
            ['phone'],
        ],
        'url' => [
            ['link'],
            // TODO not validate russian url, for example "http://капоэйра-детям.рф"
//            ['site']
        ],
        'max' => [
            ['image_size', 500000]
        ]
    ];

    public function getBranch($id, $lang = '')
    {
        $data = $this->query("SELECT b.id, b.image, b.phone, b.link,
            concat(co.{$lang}, ', ', ci.{$lang}, ', ', b.street, ', ', b.house, '/', b.block) as address,
                                         u.nickname, u.lastname, u.firstname, b.age_groups, b.site, schedule,
                                         b.curator, b.user_id, co.{$lang} AS country, ci.{$lang} AS city
            FROM branches as b 
            INNER JOIN users as u ON b.user_id = u.id
            LEFT JOIN countries AS co ON b.country = co.id
            LEFT JOIN cities AS ci ON b.city = ci.id
            WHERE b.id = {$id}")[0];
        return $data;
    }
}
