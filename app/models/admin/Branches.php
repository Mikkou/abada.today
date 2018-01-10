<?php

namespace app\models\admin;

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
        'curator' => '',
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
            ['site']
        ],
    ];

    public function getAddress($data)
    {
        $str = '';
        if (isset($data['country'])) {
            $str .= $data['country'];
        }
        if (isset($data['city'])) {
            $str .= ', ' . $data['city'];
        }
        if (isset($data['street'])) {
            $str .= ', ' . $data['street'];
        }
        if (isset($data['house'])) {
            $str .= ', ' . $data['house'];
        }
        if (isset($data['block'])) {
            $str .= '/' . $data['block'];
        }
        return $str;
    }
}