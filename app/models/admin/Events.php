<?php

namespace app\models\admin;

use fw\core\base\Model;

class Events extends Model
{

    public $rules = [
        'required' => [
            ['name'],
            ['begin_date'],
            ['end_date'],
            ['country'],
        ],
        'greaterThen' => [
            ['begin_date'],
        ],
        'url' => [
            ['vk'],
        ],
        'lengthMax' => [
            ['description', 4096],
            ['house', 4],
            ['block', 3],
        ],
        'max' => [
            ['image_size', 500000]
        ]
    ];

    public function __construct()
    {
        parent::__construct();
        $this->table = 'events';
    }

    public function getEventsData()
    {
        $data = $this->query("SELECT * FROM events ORDER BY begin_date ASC");
//        dump($data);

        $count = count($data);
        for ($i = 0; $i < $count; $i++) {
            $date = $data[$i]['begin_date'];
            $dateAr = explode('-', $date);
            $month = $this->getTextMonth($dateAr[1], true);
            $data[$i]['begin_date'] = $dateAr[2] . ' ' . $month;


        }

        return $data;
    }

    public function deleteEvent($id)
    {
        $this->deleteObj($id, 'events');
        return true;
    }
}