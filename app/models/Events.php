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
            WHERE begin_date > NOW() AND coord_x IS NOT NULL AND (category = 0 OR category = 1) ORDER BY begin_date ASC");

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
            WHERE begin_date > NOW() AND coord_x IS NOT NULL AND ({$str}) ORDER BY begin_date ASC");
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

    protected function getTextMonth($number, $modified)
    {
        $result = '';
        switch ($number) {
            case '1':
                $result = ($modified) ? 'января' : 'январь';
                break;
            case '2':
                $result = ($modified) ? 'февраля' : 'февраль';
                break;
            case '3':
                $result = ($modified) ? 'марта' : 'март';
                break;
            case '4':
                $result = ($modified) ? 'апреля' : 'апрель';
                break;
            case '5':
                $result = ($modified) ? 'мая' : 'май';
                break;
            case '6':
                $result = ($modified) ? 'июня' : 'июнь';
                break;
            case '7':
                $result = ($modified) ? 'июля' : 'июнь';
                break;
            case '8':
                $result = ($modified) ? 'августа' : 'август';
                break;
            case '9':
                $result = ($modified) ? 'сентября' : 'сентябрь';
                break;
            case '10':
                $result = ($modified) ? 'октября' : 'октябрь';
                break;
            case '11':
                $result = ($modified) ? 'ноября' : 'ноябрь';
                break;
            case '12':
                $result = ($modified) ? 'декабря' : 'декабрь';
                break;
        }
        return $result;
    }

    public function getEvent($id)
    {
        $data = $this->query("SELECT * FROM events WHERE begin_date > NOW() AND id = {$id} ORDER BY begin_date ASC");
        // minor modified data
        $result = [];
        foreach ($data as $event) {
            $event['image'] = ($event['image']) ? $event['image'] : '\public\images\pic01.jpg';
            $beginDate = explode('-', $event['begin_date']);
            $day = $beginDate[2];
            $month = $this->getTextMonth($beginDate[1], true);
            $year = $beginDate[0];
            $event['begin_date'] = (int)$day . ' ' . $month . ' ' . $year;

            $endDate = explode('-', $event['end_date']);
            $day = $endDate[2];
            $month = $this->getTextMonth($endDate[1], true);
            $year = $endDate[0];
            $event['end_date'] = (int)$day . ' ' . $month . ' ' . $year;

            $event['name'] = (empty($event['name'])) ? 'Мероприятие' : $event['name'];

            $result[] = $event;
        }
        return $result;
    }

    public function getCities($id, $lang)
    {
        return $this->query("SELECT id, {$lang} AS name FROM cities WHERE country_id = {$id} ORDER BY {$lang}");
    }
}
