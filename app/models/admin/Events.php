<?php

namespace app\models\admin;

use fw\core\base\Model;

class Events extends Model
{
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