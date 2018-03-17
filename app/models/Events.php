<?php

namespace app\models;

use \fw\core\base\Model;

class Events extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'events';
    }

    public $attributes = [
        'name' => '',
        'begin_date' => '',
        'end_date' => '',
        'country' => '',
        'city' => '',
        'street' => '',
        'house' => '',
        'block' => '',
        'organizer' => '',
        'guest' => '',
        'image' => '',
        'vk' => '',
        'description' => '',
        'event_type' => '',
        'category' => '',
        'coord_x' => '',
        'coord_y' => '',
        'user_id' => ''
    ];

    public $rules = [
        'required' => [
            ['name'],
            ['begin_date'],
            ['end_date'],
            ['country'],
            ['city']
        ],
        'greaterThen' => [
            ['begin_date']
        ],
        'url' => [
            ['vk']
        ],
        'lengthMax' => [
            ['description', 4096],
            ['house', 4],
            ['block', 3],
        ],
        'maxImage' => [
            ['image_size', 500000]
        ]
    ];

    public function getEvents($params = [], $lang)
    {
        if (!isset($params['categories'])) {

            $data = $this->query("SELECT e.id, e.name, e.begin_date, e.end_date, e.dates, e.organizer, e.street,
                e.house, e.block, e.guest, 
                e.professor, e.image, e.vk, e.category, e.description, e.event_type, user_id, e.coord_x, e.coord_y,
                ci.{$lang} AS city, co.{$lang} AS country
            FROM events AS e
            LEFT JOIN cities AS ci ON ci.id = e.city
            LEFT JOIN countries AS co ON co.id = e.country
            WHERE end_date > NOW() AND coord_x IS NOT NULL AND (category = 0 OR category = 1) ORDER BY begin_date ASC");

        } else {

            $arrayCat = explode(',', htmlspecialchars($params['categories']));
            $str = '';
            foreach ($arrayCat as $v) {

                if (empty($str))  {
                    $str .= 'category=' . $v;
                } else {
                    $str .= ' OR category=' . $v;
                }

            }

            $data = $this->query("SELECT e.id, e.name, e.begin_date, e.end_date, e.dates, e.organizer, e.street,
                e.house, e.block, e.guest, 
                e.professor, e.image, e.vk, e.category, e.description, e.event_type, user_id, e.coord_x, e.coord_y,
                ci.{$lang} AS city, co.{$lang} AS country 
            FROM events AS e
            LEFT JOIN cities AS ci ON ci.id = e.city
            LEFT JOIN countries AS co ON co.id = e.country
            WHERE end_date > NOW() AND coord_x IS NOT NULL AND ({$str}) ORDER BY begin_date ASC");
        }

        // minor modified data
        $result = [];
        foreach ($data as $event) {
            $event['image'] = ($event['image']) ? $event['image'] : '\public\images\pic01.jpg';
            $beginDate = explode('-', $event['begin_date']);
            $day = (int)$beginDate[2];
            if ($event['guest'] === '-') {
                $event['guest'] = '';
            }
            $month = (int)$beginDate[1];
            $year = substr($beginDate[0], 2, 3);
            $event['begin_date'] = $day . '.' . $month . "'" . $year;
            $result[] = $event;
        }
        return $result;
    }

    public function getCities($id, $lang)
    {
        return $this->query("SELECT id, {$lang} AS name FROM cities WHERE country_id = {$id} ORDER BY {$lang}");
    }

    public function modifiedCat($cat)
    {
        $count = count($cat);
        for ($i = 0; $i < $count; $i++) {
            if ($i < 2) {
                $cat[$i]['checked'] = 'checked';
            } else {
                $cat[$i]['checked'] = '';
            }
        }
        return $cat;
    }
}
