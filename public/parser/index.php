<?php

define('ROOT', $_SERVER['DOCUMENT_ROOT']);
define('WWW', $_SERVER['DOCUMENT_ROOT'] . '/public');

/* local
define('DB_NAME', 'id3052876_capo');
define('DB_USER', 'id3052876_capo');
define('DB_PASS', 'mrmikkou1242');
*/

define('DB_NAME', 'capo');
define('DB_USER', 'capo');
define('DB_PASS', '1111');


include_once ROOT . '\vendor\autoload.php';
include_once ROOT . '\vendor\fw\libs\simplehtmldom\Simple_html_dom.php';
require_once WWW . '\parser\DbSimple-master\lib\config.php';
require_once WWW . '\parser\DbSimple-master\lib\DbSimple\Generic.php';
require_once WWW . '\parser\DBHelper.php';

$urls = [
    2017 => 'https://docs.google.com/spreadsheets/d/1XE5U5KnBDOQJO0ihrpaBHCZFGu4abcfXWnXkfvuh1Nw/pubhtml?gid=831378598&single=true&widget=false&headers=false&range=A1:H150&chrome=false',
    2018 => 'https://docs.google.com/spreadsheets/d/1XE5U5KnBDOQJO0ihrpaBHCZFGu4abcfXWnXkfvuh1Nw/pubhtml?gid=252773673&single=true&widget=false&headers=false&range=A1:H150&chrome=false',
];

$parser = new Parser();

foreach ($urls as $year => $url) {
    $parser->start($url, $year);
}

class Parser
{
    protected $simpleDom;
    protected $year;
    protected $months = [
        'JANEIRO' => '01',
        'FEVEREIRO' => '02',
        'MARÇO' => '03',
        'ABRIL' => '04',
        'MAIO' => '05',
        'JUNHO' => '06',
        'JULHO' => '07',
        'AGOSTO' => '08',
        'SETEMBRO' => '09',
        'OUTUBRO' => '10',
        'NOVEMBRO' => '11',
        'DEZEMBRO' => '12'
    ];

    public function __construct()
    {
        $this->simpleDom = new simple_html_dom();
        $this->db = \parser\DBHelper::getInstance('');
    }


    public function start($url, $year)
    {
        $this->year = $year;
        $html = $this->getHtml($url);

        $this->simpleDom->load($html);
        $object = $this->simpleDom->find('.waffle');
        $objContent = $object[0]->children[1]->children;

        $countRows = count($objContent);

        $numberOfMonth = '';
        $arrayEvents = [];
        for ($i = 0; $i < $countRows; $i++) {
            $resultArray = [];
            // пропускаем пурвую строку заголовка талицы
            $data = $objContent[$i]->children[1]->plaintext;
            if (count($objContent[$i]->children) < 9 || $data === 'DATA' || empty($data)) {
                continue;
            }

            ////// начинаем распаршивать события

            // проверка: название месяца или перечень чисел
            if (array_key_exists($data, $this->months)) {
                $numberOfMonth = $this->months[$data];
                continue;
            }

            $stringNumbersMonth = $data;
            // получение начальной даты, конечной и строки со всеми датами
            $arrayDates = $this->getDates($stringNumbersMonth, $numberOfMonth);

            // получаем организатора
            $organizer = $objContent[$i]->children[2]->plaintext;

            // получаем город
            $city = $objContent[$i]->children[3]->plaintext;

            // получаем страну
            $country = $objContent[$i]->children[4]->plaintext;

            // получаем почетных главных гостей
            $vipGeusts = $objContent[$i]->children[5]->plaintext;

            // получаем профессора
            $professor = $objContent[$i]->children[6]->plaintext;

            // получаем имя события
            $eventsName = $objContent[$i]->children[7]->plaintext;

            // если пусто
            if (empty($organizer) && empty($city) && empty($country) && empty($vipGeusts) && empty($professor) &&
                empty($eventsName)
            ) {
                continue;
            }

            $resultArray['begin_date'] = $arrayDates[0];
            $resultArray['end_date'] = $arrayDates[count($arrayDates) - 1];
            $resultArray['dates'] = implode('|', $arrayDates);
            $resultArray['organizer'] = $organizer;
            $resultArray['city'] = $city;
            $resultArray['country'] = $country;
            $resultArray['mestre_mestrando'] = $vipGeusts;
            $resultArray['professor'] = $professor;
            $resultArray['name'] = $eventsName;

            $arrayEvents[] = $resultArray;
        }


        // запись в бд
        $countEvents = count($arrayEvents);
        for ($t = 0; $t < $countEvents; $t++) {
            $array = $arrayEvents[$t];
            $this->db->query("INSERT INTO events (?#) VALUES (?a)",
                array_keys($array), array_values($array));
        }
    }

    /**
     * @param $stringNumbers - dirty string with the numbers of month
     * @param $numberOfMonth - number of month, for example - 01 Janeiro
     * @return array
     */
    public function getDates($stringNumbers, $numberOfMonth)
    {
        if (strpos($stringNumbers, '.') !== false && strpos($stringNumbers, 'e') !== false &&
            strpos($stringNumbers, ',') === false) {
            $arrayNumbers = explode('.', $stringNumbers);
        } else {
            $arrayNumbers = explode(',', $stringNumbers);
        }

        $arrayNumbers = $this->checkExplodeDate($arrayNumbers, $numberOfMonth, $stringNumbers);
        $count = count($arrayNumbers);
        $result = [];
        for ($i = 0; $i < $count; $i++) {
            $monthAndDay = trim($arrayNumbers[$i]);
            $result[] = $this->year . '-' . $monthAndDay;
        }
        return $result;
    }

    public function checkExplodeDate($arrayNumbers, $numberOfMonth, $stringNumbers)
    {
        $result = [];
        foreach ($arrayNumbers as $value) {
            $value = trim($value);
            if (strlen(trim($value)) < 3) {
                if ((int)$value === 31 && count($arrayNumbers) > 1 || $stringNumbers === '30, 01 e 02') {
                    $month = '0' . ((int)$numberOfMonth - 1);
                    $result[] = ($value < 10) ? $month . '-0' . $value : $month . '-' . $value;
                } else {
                    $result[] = ($value < 10) ? $numberOfMonth . '-0' . (int)$value : $numberOfMonth . '-' . (int)$value;
                }
            } else {
                // перечисление двух дней через союх И
                if (strpos($value, 'e') !== false) {
                    $array = explode('e', $value);
                    $array[0] = trim($array[0]);
                    $array[1] = ((int)trim($array[1]) > 9) ? (int)trim($array[1]) : trim($array[1]);
                    $result[] = (($array[0] < 10) ? $numberOfMonth . '-0' . (int)$array[0] :
                        $numberOfMonth . '-' . $array[0]);
                    $result[] = (($array[1] < 10) ? $numberOfMonth . '-0' . (int)$array[1] : $numberOfMonth .
                        '-' . $array[1]);
                    // с такого по такой-то день
                } elseif (strpos($value, 'a') !== false && strpos($value, 'Maio') === false) {
                    $array = explode('a', $value);
                    $savedNumberOfMonth = $numberOfMonth;
                    if (strpos($value, '/') !== false) {
                        $numberOfMonth = explode('/', $array[1])[1];
                    }
                    if ((int)$array[0] > (int)$array[1] && $value === '27 a 02/04') {
                        $result[] = '03-27';
                        $result[] = '03-28';
                        $result[] = '03-29';
                        $result[] = '03-30';
                        $result[] = '03-31';
                        $result[] = '04-01';
                        $result[] = '04-02';
                    } else {
                        for ($i = (int)trim($array[0]); $i <= (int)trim($array[1]); $i++) {
                            $result[] = ($i < 10) ? $numberOfMonth . '-0' . $i : $numberOfMonth . '-' . $i;
                        }
                    }
                    // return number of month
                    $numberOfMonth = $savedNumberOfMonth;
                } elseif (strpos($value, '-') !== false) {
                    $array = explode('-', $value);
                    for ($i = (int)trim($array[0]); $i <= (int)trim($array[1]); $i++) {
                        $result[] = ($i < 10) ? $numberOfMonth . '-0' . $i : $numberOfMonth . '-' . $i;
                    }
                } else {
                    $result[] = (trim((int)$value < 10) ? $numberOfMonth . '-0' . (int)trim($value) :
                        $numberOfMonth . '-' . (int)trim($value));
                }
            }
        }
        return $result;
    }

    public function getHtml($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:49.0) Gecko/20100101 Firefox/49.0');
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}

