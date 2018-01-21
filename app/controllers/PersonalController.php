<?php

namespace app\controllers;

use app\models\Personal;
use fw\core\base\View;

class PersonalController extends AppController
{
    private static $model;

    public function __construct($route)
    {
        parent::__construct($route);
        $this->checkPermission();
        self::$model = new Personal();
    }

    public function indexAction($data, $langT, $lang)
    {
        $data = self::$model->getPersonalData();
        View::setMeta($langT['own_cabinet']);
        $this->set(compact('data', 'langT', 'lang'));
    }

    public function editAction($data, $langT, $lang)
    {
        $data = self::$model->getPersonalData();
        $this->set(compact('data', 'langT', 'lang'));
        View::setMeta($langT['edit_personal_data']);
    }

    public function saveAction($data, $langT, $lang)
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

        if (!self::$model->validate($data, $lang, $langT)) {
            self::$model->getErrors();
            $_SESSION['form_data'] = $data;
            redirect();
        }

        $str = "nickname = '{$data['nickname']}', lastname = '{$data['lastname']}',  firstname = '{$data['firstname']}'";

        if (self::$model->update('users', $str, $data['id'])) {
            // refresh nickname in him branch
            self::$model->query("UPDATE branches SET curator = '{$data['nickname']}' WHERE user_id = {$data['id']}");
            $_SESSION['success'] = $langT['data_saved'];
            self::$model->refreshUserSession();
        } else {
            $_SESSION['success'] = $langT['error_data_was_not_save'];
        }
        redirect('/personal');
    }

    public function myEventsAction($data, $langT, $lang)
    {
        $this->checkPermission();
        $events = self::$model->getEvents($lang);
        View::setMeta($langT['my_events']);
        $this->set(compact('events', 'langT', 'lang'));
    }

    public function deleteEventAction($data, $langT, $lang)
    {
        if (empty($data) || (int)$data['id'] < 1) redirect();
        self::$model->deleteObj($data['id'], 'events');
        $_SESSION['success'] = $langT['event_was_successfully_delete'];
        $this->view = false;
        $_SESSION['user']['c_own_events'] -= 1;
        redirect('/personal/my-events');
    }

    public function myBranchesAction($data, $langT, $lang)
    {
        $this->checkPermission();
        $branches = self::$model->getBranches($lang);
        View::setMeta($langT['my_branches']);
        $this->set(compact('branches', 'langT', 'lang'));
    }

    public function deleteBranchAction($data, $langT, $lang)
    {
        $this->checkPermission();
        if (empty($data) || (int)$data['id'] < 1) redirect();
        self::$model->deleteObj($data['id'], 'branches');
        $_SESSION['success'] = $langT['branch_was_successfully_delete'];
        $this->view = false;
        $_SESSION['user']['c_own_branches'] -= 1;
        redirect('/personal/my-branches');
    }

    public function editBranchAction($data, $langT, $lang)
    {
        $this->checkPermission();
        if (empty($data) || (int)$data['id'] < 1) redirect();

        $branch = self::$model->getBranch($data['id'], $lang);
        $countries = self::$model->getAllCountries($lang, $branch['country_id']);
        $countrysCities = self::$model->getCitiesByCountry($branch['country_id'], $branch['city_id'], $lang);

        View::setMeta($langT['edit_branch']);
        $this->set(compact('branch', 'langT', 'lang', 'countries', 'countrysCities'));
    }

    public function saveBranchAction($data, $langT, $lang)
    {
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

        $data = self::$model->preparationValidationImage($data, $lang, $langT);

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
            $_SESSION['success'] = $langT['data_saved'];
            self::$model->refreshUserSession();
        } else {
            $_SESSION['success'] = $langT['error_data_was_not_save'];
        }
        redirect('/personal/my-branches');
    }

    public function editEventAction($data, $langT, $lang)
    {
        $this->checkPermission();
        if (empty($data) || (int)$data['id'] < 1) redirect();

        $event = self::$model->getEventById($data['id'], $lang);
        $countries = self::$model->getAllCountries($lang);
        $categories = self::$model->getEventsCategories();
        $countrysCities = self::$model->getCitiesByCountry($event['country_id'], $event['city_id'], $lang);

        $toggleCategory = $categories[(int)$event['category']][$lang];
        unset($categories[(int)$event['category']]);
        $checked = ($event['event_type'] === '1') ? 'checked' : '';
        View::setMeta($langT['edit_event']);
        $this->set(compact('event', 'categories', 'toggleCategory', 'checked', 'langT', 'lang',
            'countries', 'countrysCities'));
    }

    public function saveEventAction($data, $langT, $lang)
    {
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

        $data = self::$model->preparationValidationImage($data, $lang, $langT);

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
            $_SESSION['success'] = $langT['data_saved'];
            self::$model->refreshUserSession();
        } else {
            $_SESSION['success'] = $langT['error_data_was_not_save'];
        }
        redirect('/personal/my-events');
    }

    public function changePasswordAction($data, $langT, $lang)
    {
        if (isset($data['password'])) {
            $newPass = $data['password'];
            $id = $_SESSION['user']['id'];

            self::$model->attributes = [
                'password' => '',
            ];

            self::$model->rules = [
                'lengthMin' => [
                    ['password', 6],
                ]
            ];

            if (!self::$model->validate($data, $lang, $langT)) {
                self::$model->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }

            self::$model->changePassword($id, $newPass);
            $_SESSION['success'] = $langT['password_was_save'];
            redirect('/personal');
        }
        $this->set(compact('langT', 'lang'));
        View::setMeta($langT['change_password']);
    }

}
