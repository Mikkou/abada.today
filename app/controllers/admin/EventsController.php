<?php

namespace app\controllers\admin;

use app\models\admin\Events;
use fw\core\base\View;

class EventsController extends AppController
{
    private static $model;

    public function __construct($route)
    {
        parent::__construct($route);
        self::$model = new Events();
    }

    public function indexAction()
    {
        if (!isset($_SESSION['user'])) redirect('/main/login');
        $data = self::$model->getEventsData();
        View::setMeta('События');
        $this->set(compact('data'));
    }

    public function editAction($params)
    {
        if (!isset($_SESSION['user'])) redirect('/main/login');
        $event = self::$model->getEventById($params['id'], 'ru');
        $categories = self::$model->getEventsCategories();
        $countries = self::$model->getAllCountries();
        $countrysCities = self::$model->getCitiesByCountry($event['country_id'], $event['city_id'], 'ru');
        if (is_null($event['category'])) {
            $toggleCategory = '';
        } else {
            $toggleCategory = $categories[(int)$event['category']]['ru'];
            unset($categories[(int)$event['category']]);
        }
        $checked = ($event['event_type'] === '1') ? 'checked' : '';
        View::setMeta('Редактировать событие');
        $this->set(compact('event', 'categories', 'toggleCategory', 'checked', 'countries', 'countrysCities'));
    }

    public function saveEventAction($params)
    {
        if (!isset($_SESSION['user'])) redirect('/main/login');
        $data = $params;
        self::$model->attributes = [
            'name' => '',
            'begin_date' => '',
            'end_date' => '',
            'country' => '',
            'city' => '',
            'street' => '',
            'house' => '',
            'block' => '',
            'guest' => '',
            'organizer' => '',
            'vk' => '',
            'category' => '',
            'event_type' => '',
            'description' => '',
            'coord_x' => '',
            'coord_y' => '',
        ];

        self::$model->rules = [
            'required' => [
                ['name'],
                ['begin_date'],
                ['end_date'],
                ['country'],
            ],
            'url' => [
                ['vk']
            ],
            'lengthMax' => [
                ['description', 4096],
                ['house', 4],
                ['block', 3]
            ],
            'max' => [
                ['image_size', 500000]
            ]
        ];

        // for checking size of image
        if (!empty($_FILES)) {
            $data['image_size'] = $_FILES['image']['size'];
            self::$model->attributes['image_size'] = '';
        }

        self::$model->load($data);

        if (!self::$model->validate($data, $this->lang, $this->langT)) {
            self::$model->getErrors();
            $_SESSION['form_data'] = $data;
            redirect();
        }

        if (isset(self::$model->attributes['image_size'])) {
            unset(self::$model->attributes['image_size']);
            unset($data['image_size']);
        }

        // check event type
        $data['event_type'] = (isset($data['event_type'])) ? 1 : 0;

        // if city is new -> saving him and get his id
        if (isset($data['city']) && strpos($data['city'], 'new_') === 0) {
            $data['city'] = self::$model->putNewCity($data['country'], $data['city'], 'ru');
        }

        if (!empty($data['house'])) {
            $data['house'] = (int)$data['house'];
            if ($data['house'] === 0) {
                unset($data['house']);
            }
        }

        $data['description'] = htmlspecialchars($data['description'], ENT_QUOTES);

        $str = "name = '{$data['name']}', begin_date = '{$data['begin_date']}', end_date = '{$data['end_date']}',
         country = '{$data['country']}', city = '{$data['city']}', street = '{$data['street']}',
          house = '{$data['house']}', block = '{$data['block']}', guest = '{$data['guest']}',
          organizer = '{$data['organizer']}', description = '{$data['description']}', category = {$data['category']},
           event_type = {$data['event_type']}, vk = '{$data['vk']}', coord_x = '{$data['coord_x']}',
            coord_y = '{$data['coord_y']}'";



        if (!empty($_FILES['image']['tmp_name'])) {
            $data['image'] = self::$model->saveImage(true, $data['id'], 'events');
            $str .= ", image = '{$data['image']}'";
        }

        if (self::$model->update('events', $str, $data['id'])) {
            $_SESSION['success'] = 'Данные сохранены.';
            self::$model->refreshUserSession();
        } else {
            $_SESSION['success'] = 'Ошибка! Данные не были сохранены.';
        }
        redirect('/admin/events');
    }

    public function addAction($data)
    {
        if (!isset($_SESSION['user'])) redirect('/main/login');
        if ((int)$_SESSION['user']['rights'] < 10) redirect();

        self::$model->attributes = [
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
            'description' => '',
            'vk' => '',
            'event_type' => '',
            'category' => '',
            'coord_x' => '',
            'coord_y' => '',
            'user_id' => '',
        ];

        self::$model->rules = [
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

        if (isset($data['category'])) {

            // for checking size of image
            if (!empty($_FILES)) {
                $data['image_size'] = $_FILES['image']['size'];
                self::$model->attributes['image_size'] = '';
            }

            self::$model->load($data);
            if (!self::$model->validate($data, $this->lang, $this->langT)) {
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
                $data['city'] = self::$model->putNewCity($data['country'], $data['city'], 'ru');
            }

            $data['category'] = (int)$data['category'];
            if (!empty($data['house'])) {
                $data['house'] = (int)$data['house'];
                if ($data['house'] === 0) {
                    unset($data['house']);
                }
            }

            $data['user_id'] = 0;
            self::$model->load($data);
            if (self::$model->save('events')) {
                $_SESSION['success'] = 'Событие успешно было добавлено.';
                self::$model->refreshUserSession();
                redirect('/admin/events');
            } else {
                $_SESSION['error'] = 'Ошибка! Событие не было добавлено.';
                redirect('/admin/events/add');
            }
        }
        $countries = self::$model->getAllCountries();
        $categories = self::$model->getEventsCategories();
        View::setMeta('Админ :: Добавление события');
        $this->set(compact('categories', 'countries'));
    }

    public function deleteAction($params)
    {
        if (!isset($_SESSION['user'])) redirect('/main/login');
        if (empty($params)) redirect();
        $id = (int)$params['id'];
        if ($id === 0) redirect();
        self::$model->deleteEvent($id);
        $this->view = false;
        redirect('/admin/events');
    }
}