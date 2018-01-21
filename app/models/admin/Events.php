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
        return $this->query("SELECT * FROM events WHERE begin_date > NOW() ORDER BY begin_date ASC");
    }

    public function deleteEvent($id)
    {
        $this->deleteObj($id, 'events');
        return true;
    }
}