<?php
class register
{
    protected static $object;

    public function set ($alias, $object)
    {
        self::$object[$alias] = $object;
    }

    public static function get ($name)
    {
        return self::$object[$name];
    }

    public static function _unset ($alias)
    {
        unset(self::$object[$alias]);
    }
}