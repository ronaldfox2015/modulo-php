<?php

namespace Bumeran\Common;

/**
 * Abstract Enum
 *
 * @link http://stackoverflow.com/questions/254514/php-and-enumerations
 */
abstract class AbstractEnum
{
    /** @var array */
    protected static $constCacheArray = null;

    /**
     * Return all Constants
     *
     * @return array
     */
    public static function getConstants()
    {
        if (static::$constCacheArray == null) {
            static::$constCacheArray = [];
        }

        $calledClass = get_called_class();

        if (! array_key_exists($calledClass, static::$constCacheArray)) {
            $reflect = new \ReflectionClass($calledClass);
            static::$constCacheArray[$calledClass] = $reflect->getConstants();
        }

        return static::$constCacheArray[$calledClass];
    }

    /**
     * Get Enum value
     *
     * @param string $name
     * @return null|string
     */
    public static function getValue($name)
    {
        $constants = static::getConstants();

        return $constants[strtoupper($name)];
    }

    /**
     * Check if enum name is valid
     *
     * @param  string  $name
     * @param  boolean $strict
     * @return boolean
     */
    public static function isValidName($name, $strict = false)
    {
        $constants = static::getConstants();

        if ($strict) {
            return array_key_exists($name, $constants);
        }

        $keys = array_map('strtolower', array_keys($constants));

        return in_array(strtolower($name), $keys);
    }

    /**
     * Check if enum value is valid
     *
     * @param  mixed   $value
     * @param  boolean $strict
     * @return boolean
     */
    public static function isValidValue($value, $strict = true)
    {
        $values = array_values(static::getConstants());

        return in_array($value, $values, $strict);
    }
}
