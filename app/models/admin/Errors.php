<?php

namespace app\models\admin;

use fw\core\base\Model;

class Errors extends Model
{
    protected static $pathErrorsLog = '../tmp/errors.log';
    public function getListErrors()
    {
        if (strpos(file_get_contents(self::$pathErrorsLog), "\r\n") === false) {
            $data = str_replace("\r\n", "", file_get_contents(self::$pathErrorsLog));
            $dirtArrayErrors = explode("===========================\n", file_get_contents(self::$pathErrorsLog));
        } else {
            $data = str_replace("\r\n", "", file_get_contents(self::$pathErrorsLog));
            file_put_contents(self::$pathErrorsLog, $data);
            $dirtArrayErrors = file(self::$pathErrorsLog);
        }

        $cleanErros = [];
        foreach ($dirtArrayErrors as $k => $v) {

            if (strpos($v, '=======') === false) {
                $singleError = [];
                $a = explode('|', trim($v));
                $singleError['date'] = str_replace(['[', ']'], '', trim(explode('Текст ошибки:', $a[0])[0]));
                if (!isset(explode('Текст ошибки:', $a[0])[1])) continue;
                $singleError['text'] = trim(explode('Текст ошибки:', $a[0])[1]);
                $singleError['file'] = trim(str_replace(['Файл:', ','], '', $a[1]));
                $singleError['line'] = trim(str_replace('Строка:', '', $a[2]));
                $cleanErros[] = $singleError;

            }
        }
        return $cleanErros;
    }

    public function clean()
    {
        if (file_exists(self::$pathErrorsLog)) {
            file_put_contents(self::$pathErrorsLog, '');
            return true;
        } else {
            throw new \Exception("Файл <b>" . self::$pathErrorsLog . "</b> не найден");
        }
    }
}