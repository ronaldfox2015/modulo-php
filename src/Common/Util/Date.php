<?php

namespace Bumeran\Common\Util;

use DateTime;

/**
 * Class Date Utils
 *
 * @package Bumeran\Common\Util
 * @author  Pedro Vega <pakgva@gmail.com>
 * @copyright (c) Otbis, 2016
 */
final class Date
{
    /**
     * Time elapsed to string
     *
     * @param string|DateTime $date
     * @param string $prefix
     * @param bool $full
     * @param bool $formal
     * @return string
     */
    public static function timeElapsed($date, $prefix = 'hace ', $full = false, $formal = true)
    {
        $now = new DateTime;
        $date = ($date instanceof DateTime) ? $date : new DateTime($date);

        $diff = $now->diff($date);
        $days = $diff->d;
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = [
            'y' => 'año',
            'm' => 'mes',
            'w' => 'semana',
            'd' => 'día',
            'h' => 'hora',
            'i' => 'minuto',
            's' => 'segundo',
        ];

        $stringPlural = [
            'año' => 'años',
            'mes' => 'meses',
            'semana' => 'semanas',
            'día' => 'días',
            'hora' => 'horas',
            'minuto' => 'minutos',
            'segundo' => 'segundos'
        ];

        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . ($diff->$k > 1 ? $stringPlural[$v] : $v);
            } else {
                unset($string[$k]);
            }
        }

        if (! $full) {
            $string = array_slice($string, 0, 1);
        }

        $string = $string ? $prefix . implode(', ', $string) : '0 segundos';

        if (! $formal) {
            return $string;
        }

        if (preg_match('/(segundo|minuto)/', $string)) {
            $string = 'Hoy';
        } elseif (preg_match('/(\d+) hora/', $string, $match)) {
            // Condicion util para poder validar el numero de horas con el dia
            $calcDate = new DateTime(date("Y-m-d H:i:s", strtotime("-" . (int)$match[1] . " hours")));
            $string = $calcDate->format('d') != $now->format('d') ?
                'Ayer' :
                'Hoy';
        } elseif (preg_match('/1 día/', $string)) {
            $string = 'Ayer';
        } elseif (preg_match('/(\d+) semana/', $string, $match)) {
            $string = $prefix . $days . ' ' . $stringPlural['día'];
        }

        return strtolower($string);
    }

    /**
     * Get age in from birth date
     *
     * @param  $date
     * @return int
     */
    public static function getAgeFromDate($date)
    {
        if ($date instanceof \DateTime) {
            $date = date_create(
                $date->format('Y-m-d H:i:s'),
                $date->getTimezone()
            );
        } else {
            $date = date_create($date);
        }

        return date_diff($date, date_create('now'))->y;
    }

    public static function longFormat($date, $conector = 'de')
    {
        $date = new DateTime($date);
        $month = Date::month();
        return sprintf('%s %s %s', $date->format('d'), $conector, $month[$date->format('n')]);
    }

    public static function month()
    {
        return [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio ',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];
    }
}
