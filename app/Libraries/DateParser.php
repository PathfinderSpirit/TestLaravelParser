<?php

namespace App;

use DateTime;


class DateParser
{
    public static function parseDate($value)
    {
        $alphaDate = preg_split("/[ :]+/", $value);
        $date = new DateTime();

        if(count($alphaDate) == 4)
        {
            $currentDate = getdate();
            if($alphaDate[0] == 'сегодня'){
                $date->setDate($currentDate['year'], $currentDate['mon'], $currentDate['mday']);
                
            }
            else if($alphaDate[0] == 'вчера'){
                $date->setDate($currentDate['year'], $currentDate['mon'], $currentDate['mday']-1);
            }
            $date->setTime($alphaDate[2], $alphaDate[3]);
        }
        else{
            $date->setDate($alphaDate[2], self::parseMonth($alphaDate[1]), $alphaDate[0]);
            $date->setTime($alphaDate[4], $alphaDate[5]);
        }
        return $date;
    }

    public function parseMonth($month)
    {
        $monthArray =
        [
            'января' => 1,
            'февраля' => 2,
            'марта' => 3,
            'апреля' => 4,
            'мая' => 5,
            'июня' => 6,
            'июля' => 7,
            'августа' => 8,
            'сентября' => 9,
            'октября' => 10,
            'ноября' => 11,
            'декабря' => 12
        ];
        return $monthArray[$month];
    }
}
