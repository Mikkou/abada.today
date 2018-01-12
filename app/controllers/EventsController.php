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

    public function indexAction($params)
    {
        $langT = $params['langText'];
        $lang = $params['lang'];
        if (!isset($params['id'])) {
            $events = self::$model->getEvents([], $lang);
            $categories = $this->modifiedCat(self::$categories);
            $this->set(compact('events', '', 'categories', 'langT', 'lang'));
            $title = ($lang === 'en') ? 'Events' : 'События';
            View::setMeta($title,'Мировые события школы ABADA-Capoeira', 'capoeira, abada-capoeira');
        } else {
            // if click on branch
            $event = self::$model->getEventById($params['id'], $lang);

            // check if don't find anything
            if (!$event) {
                $json = json_encode($event);
                throw new \Exception("Не найден объект {$params['id']} - {$json}");
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
            $category = $this->getCategoryName($event, $lang);
            if ($lang === 'ru') {
                $eventType = ($event['event_type'] == 1) ? 'открытое' : 'закрытое';
            } else {
                $eventType = ($event['event_type'] == 1) ? 'open' : 'close';
            }

            $this->view = 'card';
            View::setMeta($event['name']);
            $this->set(compact('event', 'address', 'eventType', 'category', 'langT', 'lang'));
        }
    }

    public function getCategoryName($event, $lang)
    {
        $str = '';
        if (is_null($event['category'])) {
            return '';
        }

        if ($lang === 'ru') {
            switch ((int)$event['category']) {
                case 0:
                    $str = 'Жогосы';
                    break;
                case 1:
                    $str = 'Батизады';
                    break;
                case 2:
                    $str = 'Семинары';
                    break;
                case 3:
                    $str = 'Лагеря';
                    break;
                case 4:
                    $str = 'Другое';
                    break;
            }
        } else {
            switch ((int)$event['category']) {
                case 0:
                    $str = 'jogos';
                    break;
                case 1:
                    $str = 'batizados';
                    break;
                case 2:
                    $str = 'seminars';
                    break;
                case 3:
                    $str = 'camps';
                    break;
                case 4:
                    $str = 'another';
                    break;
            }
        }

        return $str;
    }

    public function addAction($data)
    {
        $langT = $data['langText'];
        $lang = $data['lang'];

        if ((int)$_SESSION['user']['rights'] < 10) redirect();
        if (isset($data['category'])) {

            unset($data['langText']);
            unset($data['lang']);

            // for checking size of image
            if (!empty($_FILES)) {
                $data['image_size'] = $_FILES['image']['size'];
                self::$model->attributes['image_size'] = '';
            }

            self::$model->load($data);
            if (!self::$model->validate($data)) {
                self::$model->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }

            if (isset(self::$model->attributes['image_size'])) {
                unset(self::$model->attributes['image_size']);
                unset($data['image_size']);
            }

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
                $_SESSION['success'] = 'Событие успешно было добавлено.';
                self::$model->refreshUserSession();
                redirect('/personal');
            } else {
                $_SESSION['error'] = 'Ошибка! Событие не было добавлено. Обратитесь пожалуйста в техподдержку.';
                redirect('/events/add');
            }
        }
        $countries = self::$model->getAllCountries($lang);
        $categories = self::$categories;
        $title = ($lang === 'en') ? 'Add event' : 'Добавить событие';
        View::setMeta($title);
        $this->set(compact('categories', 'countries', 'langT', 'lang'));
    }

    public function getObjectsAction($params)
    {
        $this->view = false;
        $lang = $params['lang'];
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

    public function filterAction($params)
    {
        if (strlen($params['categories']) > 10) redirect();
        $lang = $params['lang'];
        $this->view = false;
        $events = self::$model->getEvents($params, $lang);
        echo json_encode($events);
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

    public function getCitiesAction($params)
    {
        $this->view = false;
        echo json_encode(self::$model->getCities($params['id'], $params['lang']));
    }
}
