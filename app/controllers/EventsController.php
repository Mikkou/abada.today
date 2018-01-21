<?php

namespace app\controllers;

use app\models\Events;
use fw\core\base\View;

class EventsController extends AppController
{
    public static $model;
    public static $categories;

    public function __construct($route)
    {
        parent::__construct($route);
        self::$model = new Events();
        self::$categories = self::$model->getEventsCategories();
    }

    public function indexAction($data, $langT, $lang)
    {
        if (!isset($data['id'])) {
            $events = self::$model->getEvents([], $lang);
            $categories = self::$model->modifiedCat(self::$categories);
            $this->set(compact('events', '', 'categories', 'langT', 'lang'));
            View::setMeta($langT['events'],'Мировые события школы ABADA-Capoeira', 'capoeira, abada-capoeira');
        } else {
            // if click on branch
            $event = self::$model->getEventById($data['id'], $lang);

            // check if don't find anything
            if (!$event) {
                $json = json_encode($event);
                throw new \Exception("Не найден объект {$data['id']} - {$json}");
            }
            $beginDate = explode('-', $event['begin_date']);
            $day = (int)$beginDate[2];
            $month = (int)$beginDate[1];
            $year = substr($beginDate[0], 2, 3);
            $event['begin_date'] = $day . '.' . $month . "'" . $year;

            $beginDate = explode('-', $event['end_date']);
            $day = (int)$beginDate[2];
            $month = (int)$beginDate[1];
            $year = substr($beginDate[0], 2, 3);
            $event['end_date'] = $day . '.' . $month . "'" . $year;

            $event['image'] = ($event['image']) ? $event['image'] : '\public\images\pic01.jpg';

            $event['description'] = self::$model->replaceParagraphOnBR($event['description']);
            $address = $this->getAddressStr($event);
            $eventType = ($event['event_type'] == 1) ? mb_strtolower($langT['open']) : $langT['close'];

            $this->view = 'card';
            View::setMeta($event['name']);
            $this->set(compact('event', 'address', 'eventType', 'category', 'langT', 'lang'));
        }
    }

    public function addAction($data, $langT, $lang)
    {
        $this->checkPermission('trainer');

        if (isset($data['category'])) {

            self::$model->preparationValidationImage($data, $lang, $langT);

            if (!empty($_FILES)) {
                $data['image'] = self::$model->saveImage();
            }

            // check event type
            $data['event_type'] = (isset($data['event_type'])) ? 1 : 0;

            // if city is new -> saving him and get his id
            if (isset($data['city']) && strpos($data['city'], 'new_') === 0) {
                $data['city'] = self::$model->putNewCity($data['country'], $data['city'], $lang);
            }

            $data['category'] = (int)$data['category'];
            if (!empty($data['house'])) {
                $data['house'] = (int)$data['house'];
            }
            $data['user_id'] = $_SESSION['user']['id'];
            self::$model->load($data);
            if (self::$model->save('events')) {
                $_SESSION['success'] = $langT['event_was_successfully_add'];
                self::$model->refreshUserSession();
                redirect('/personal');
            } else {
                $_SESSION['error'] = $langT['error_event_not_add'];
                redirect('/events/add');
            }
        }
        $countries = self::$model->getAllCountries($lang);
        $categories = self::$categories;
        View::setMeta($langT['add_event']);
        $this->set(compact('categories', 'countries', 'langT', 'lang'));
    }

    public function getObjectsAction($data, $langT, $lang)
    {
        $this->view = false;
        $events = json_encode(self::$model->getEvents([], $lang));
        echo $events;
    }

    public function getAddressStr($data)
    {
        $str = '';
        if (isset($data['country'])) {
            if ($data['country']) {
                $str .= $data['country'];
            }
        }
        if (isset($data['city'])) {
            if ($data['city']) {
                $str .= ", " . $data['city'];
            }
        }
        if (isset($data['street'])) {
            if ($data['street']) {
                $str .= ", " . $data['street'];
            }
        }
        if (isset($data['house'])) {
            if ($data['house']) {
                $str .= ", " . $data['house'];
            }
        }
        if (isset($data['block'])) {
            if ($data['block']) {
                $str .= "/" . $data['block'];
            }
        }
        return $str;
    }

    public function filterAction($data, $langT, $lang)
    {
        if (strlen($data['categories']) > 10) redirect();
        $this->view = false;
        $events = self::$model->getEvents($data, $lang);
        echo json_encode($events);
    }

    public function getCitiesAction($data, $langT, $lang)
    {
        $this->view = false;
        echo json_encode(self::$model->getCities($data['id'], $lang));
    }
}
