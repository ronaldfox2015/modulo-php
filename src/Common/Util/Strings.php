<?php

namespace Bumeran\Common\Util;

use DOMDocument;

/**
 * Class String
 *
 * @package Bumeran\Common\Util
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
final class Strings
{
    /**
     * @param $value
     * @param string $replace
     * @return string
     */
    public static function formatTitle($value, $replace = ' ')
    {
        if (strpos($value, ' y ')) {
            $replace = ' ';
        }

        $value = preg_replace('/-[-]*/', $replace, $value);
        $value = preg_replace('/-$/', $replace, $value);
        $value = preg_replace('/^-/', '', $value);
        $value = join(',', array_map('ucwords', explode(',', $value)));

        return $value;
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function sanitizeSearch($value)
    {
        $value = str_replace(
            [
            "\\", "¨", "º", "-", "~",
            "#", "@", "|", "!", "\"",
            "·", "$", "%", "&", "/",
            "(", ")", "?", "'", "¡",
            "¿", "[", "^", "`", "]",
            "+", "}", "{", "¨", "´",
            ">", "< ", ";", ",", ":",'"',"'",
            "."],
            '',
            $value
        );

        return preg_replace("/([ ]){2,}/", " ", $value);
    }

    /**
     * @param $value
     * @param string $replace
     * @return string
     */
    public static function filter($value, $replace = '-')
    {
        $value = str_replace(
            ["á", "é", "í", "ó", "ú", "ä", "ë", "ï", "ö", "ü", "ñ"],
            ["a", "e", "i", "o", "u", "a", "e", "i", "o", "u", "n"],
            mb_strtolower($value, 'UTF-8')
        );

        $value = preg_replace('/[^a-zñÑáéíóúÚ0-9-]/i', $replace, $value);
        $value = preg_replace('/-[-]*/', $replace, $value);
        $value = preg_replace('/-$/', '', $value);
        $value = preg_replace('/^-/', '', $value);

        return $value;
    }

    /**
     * @param $query
     * @return mixed|string
     */
    public static function searchQueryString($query)
    {
        $de = [" DE ", " de ", " De ", " dE "];

        $query = str_replace($de, " ", $query);
        $query = trim($query);
        $query = str_replace('%20', '', $query);
        $query = str_replace(
            ["\\", "¨", "º", "-", "~",
                "#", "@", "|", "!", "\"",
                "·", "$", "%", "&", "/",
                "(", ")", "?", "'", "¡",
                "¿", "[", "^", "`", "]",
                "+", "}", "{", "¨", "´",
                ">", "< ", ";", ",", ":", '"', "'",
                "."],
            '',
            $query
        );

        $query = preg_replace("/([ ]){2,}/", " ", $query);
        $query = filter_var($query, FILTER_SANITIZE_STRING);

        return $query;
    }

    /**
     * @param $field
     * @param string $replace
     * @return string
     */
    public static function removeLines($field, $replace = '')
    {
        return trim(str_replace(["\r"], $replace, $field));
    }

    /**
     * @param $string
     * @return mixed|string
     */
    public static function mbUcfirst($string)
    {
        return mb_convert_case($string, MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * Return pretty xml string
     *
     * @param $string
     * @return string
     */
    public static function prettyXML($string)
    {
        if (empty($string)) {
            return $string;
        }

        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($string);
        $dom->formatOutput = true;

        return $dom->saveXml();
    }

    /**
     * Return pretty json string
     *
     * @param $string
     * @return string
     */
    public static function prettyJSON($string)
    {
        return json_encode($string, JSON_PRETTY_PRINT);
    }

    /**
     * Extrar desde la derecha un determinado string
     *
     * @param string $haystack
     * @param string $replace
     * @return string
     */
    public static function rextract($haystack, $replace)
    {
        return substr($haystack, 0, strlen($haystack) - strlen($replace));
    }

    /**
     * Sanitize string with filter
     *
     * @param string $str
     * @return string
     */
    public static function sanitize($str)
    {
        return filter_var($str, FILTER_SANITIZE_STRING);
    }
}
