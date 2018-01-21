<?php

namespace app\models\admin;

use fw\core\base\Model;

class Branches extends Model
{
    public $table = 'branches';

    public $attributes;

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
        ],
        'max' => [
            ['image_size', 500000]
        ]
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