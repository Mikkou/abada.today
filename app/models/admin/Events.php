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

    public function deleteEvent($id):bool
    {
        $this->deleteObj($id, 'events');
        return true;
    }
}