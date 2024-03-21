<?php

namespace App\Enums;

abstract class Enum
{
    protected static $content = [

    ];

    public static function getContentList()
    {
        return static::$content;
    }

    public static function getKeys()
    {
        return array_keys(static::$content);
    }

    public static function getValue($id)
    {
        return static::$content[$id];
    }
}
