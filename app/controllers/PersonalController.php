<?php

namespace app\controllers;

use app\models\Personal;
use fw\core\base\View;

class PersonalController extends AppController
{
    private static $model;

    public function __construct($route, $params)
    {
        parent::__construct($route, $params);
        if (!isset($_SESSION['user'])) redirect('/');
        self::$model = new Personal();
    }

    public function indexAction($params)
    {
        $langT = $params['langText'];
        $lang = $params['lang'];
        $data = self::$model->query("SELECT * FROM users WHERE id = {$_SESSION['user']['id']}")[0];
        $title = ($lang === 'en') ? 'Personal cabinet' : 'Личный кабинет';
        View::setMeta($title);
        $this->set(compact('data', 'langT', 'lang'));
    }

    public function editAction($params)
    {
        $langT = $params['langText'];
        $lang = $params['lang'];
        $data = self::$model->query("SELECT * FROM users WHERE id = {$_SESSION['user']['id']}")[0];
        $this->set(compact('data', 'langT', 'lang'));
        $title = ($lang === 'en') ? 'Edit personal data' : 'Редактировать личные данные';
        View::setMeta($title);
    }

    public function saveAction($data)
    {
        self::$model->attributes = [
            'nickname' => '',
            'firstname' => '',
            'lastname' => '',
            'id' => '',
        ];

        self::$model->rules = [
            'withoutNumbers' => [
                ['firstname'],
                ['lastname'],
            ],
        ];

        self::$model->load($data);

        if (!self::$model->validate($data, $this->lang, $this->langT)) {
            self::$model->getErrors();
            $_SESSION['form_data'] = $data;
            redirect();
        }

        $str = "nickname = '{$data['nickname']}', lastname = '{$data['lastname']}',  firstname = '{$data['firstname']}'";

        if (self::$model->update('users', $str, $data['id'])) {
            // refresh nickname in him branch
            self::$model->query("UPDATE branches SET curator = '{$data['nickname']}' WHERE user_id = {$data['id']}");
            $_SESSION['success'] = 'Данные сохранены.';
            self::$model->refreshUserSession();
        } else {
            $_SESSION['success'] = 'Ошибка! Данные не были сохранены. Обратитесь пожалуйста в техподдержку.';
        }
        redirect('/personal');
    }

    public function myEventsAction($params)
    {
        if ((int)$_SESSION['user']['rights'] < 10) redirect();
        $langT = $params['langText'];
        $lang = $params['lang'];
        $events = self::$model->getEvents($lang);
        $title = ($lang === 'en') ? 'My events' : 'Мои события';
        View::setMeta($title);
        $this->set(compact('events', 'langT', 'lang'));
    }

    public function deleteEventAction($params)
    {
        if (empty($params) || (int)$params['id'] < 1) redirect();
        self::$model->deleteObj($params['id'], 'events');
        $_SESSION['success'] = 'Событие было успешно удалено.';
        $this->view = false;
        $_SESSION['user']['c_own_events'] -= 1;
        redirect('/personal/my-events');
    }

    public function myBranchesAction($data)
    {
        if ((int)$_SESSION['user']['rights'] < 10) redirect();
        $langT = $data['langText'];
        $lang = $data['lang'];
        $branches = self::$model->getBranches($lang);
        $title = ($lang === 'en') ? 'My branches' : 'Мои филиалы';
        View::setMeta($title);
        $this->set(compact('branches', 'langT', 'lang'));
    }

    public function deleteBranchAction($data)
    {
        if (empty($data) || (int)$data['id'] < 1) redirect();
        self::$model->deleteObj($data['id'], 'branches');
        $_SESSION['success'] = 'Филиал был успешно удален.';
        $this->view = false;
        $_SESSION['user']['c_own_branches'] -= 1;
        redirect('/personal/my-branches');
    }

    public function editBranchAction($params)
    {
        if (empty($params) || (int)$params['id'] < 1) redirect();
        if (!isset($_SESSION['user'])) redirect('/');
        $langT = $params['langText'];
        $lang = $params['lang'];
        $branch = self::$model->getBranch($params['id'], $lang);

        $countries = self::$model->getAllCountries($lang, $branch['country_id']);
        $countrysCities = self::$model->getCitiesByCountry($branch['country_id'], $branch['city_id'], $lang);

        $title = ($lang === 'en') ? 'Edit branch' : 'Редактировать филиал';
        View::setMeta($title);
        $this->set(compact('branch', 'langT', 'lang', 'countries', 'countrysCities'));
    }

    public function saveBranchAction($data)
    {
        $lang = $data['lang'];
        self::$model->attributes = [
            'country' => '',
            'city' => '',
            'street' => '',
            'house' => '',
            'block' => '',
            'image' => '',
            'phone' => '',
            'link' => '',
            'age_groups' => '',
            'site' => '',
            'schedule' => '',
            'id' => '',
        ];

        self::$model->rules = [
            'required' => [
                ['country'],
                ['city'],
                ['street'],
                ['house'],
                ['phone'],
            ],
            'url' => [
                ['link'],
                ['site']
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

        // if city is new -> saving him and get his id
        if (isset($data['city']) && strpos($data['city'], 'new_') === 0) {
            $data['city'] = self::$model->putNewCity($data['country'], $data['city'], $lang);
        }

        $str = "country = '{$data['country']}', city = '{$data['city']}', street = '{$data['street']}',
         house = '{$data['house']}', block = '{$data['block']}', phone = '{$data['phone']}', link = '{$data['link']}',
          age_groups = '{$data['age_groups']}', site = '{$data['site']}', schedule = '{$data['schedule']}'";

        if (!empty($_FILES['image']['tmp_name'])) {
            $data['image'] = self::$model->saveImage(true, $data['id'], 'branches');
            $str .= ", image = '{$data['image']}'";
        }

        if (self::$model->update('branches', $str, $data['id'])) {
            $_SESSION['success'] = 'Данные сохранены.';
            self::$model->refreshUserSession();
        } else {
            $_SESSION['success'] = 'Ошибка! Данные не были сохранены. Обратитесь пожалуйста в техподдержку.';
        }
        redirect('/personal/my-branches');
    }

    public function editEventAction($params)
    {
        if (empty($params) || (int)$params['id'] < 1) redirect();
        if (!isset($_SESSION['user'])) redirect('/');
        $langT = $params['langText'];
        $lang = $params['lang'];
        $event = self::$model->getEventById($params['id'], $lang);
        $countries = self::$model->getAllCountries($lang);
        $categories = self::$model->getEventsCategories();
        $countrysCities = self::$model->getCitiesByCountry($event['country_id'], $event['city_id'], $lang);
        $toggleCategory = $categories[(int)$event['category']][$lang];
        unset($categories[(int)$event['category']]);
        $checked = ($event['event_type'] === '1') ? 'checked' : '';
        $title = ($lang === 'en') ? 'Edit event' : 'Редактировать событие';
        View::setMeta($title);
        $this->set(compact('event', 'categories', 'toggleCategory', 'checked', 'langT', 'lang',
            'countries', 'countrysCities'));
    }

    public function saveEventAction($data)
    {
        $lang = $data['lang'];
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
                ['city'],
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
            $data['city'] = self::$model->putNewCity($data['country'], $data['city'], $lang);
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
          organizer = '{$data['organizer']}', vk = '{$data['vk']}', description = '{$data['description']}',
           category = {$data['category']}, event_type = {$data['event_type']}, coord_x = '{$data['coord_x']}',
            coord_y = '{$data['coord_y']}'";

        if (!empty($_FILES['image']['tmp_name'])) {
            $data['image'] = self::$model->saveImage(true, $data['id'], 'events');
            $str .= ", image = '{$data['image']}'";
        }

        if (self::$model->update('events', $str, $data['id'])) {
            $_SESSION['success'] = 'Данные сохранены.';
            self::$model->refreshUserSession();
        } else {
            $_SESSION['success'] = 'Ошибка! Данные не были сохранены. Обратитесь пожалуйста в техподдержку.';
        }
        redirect('/personal/my-events');
    }

    public function changePasswordAction($params)
    {
        if (isset($params['password'])) {
            $data = $params;
            $newPass = $params['password'];
            $id = $_SESSION['user']['id'];

            self::$model->attributes = [
                'password' => '',
            ];

            self::$model->rules = [
                'lengthMin' => [
                    ['password', 6],
                ]
            ];

            if (!self::$model->validate($data, $this->lang, $this->langT)) {
                self::$model->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }

            self::$model->changePassword($id, $newPass);
            $_SESSION['success'] = 'Пароль сохранен.';
            redirect('/personal');
        }
        $langT = $params['langText'];
        $lang = $params['lang'];
        $this->set(compact('langT', 'lang'));
        $title = ($lang === 'en') ? 'Change password' : 'Смена пароля';
        View::setMeta($title);
    }

}
