<?php

namespace App\Utils\Constants;

class FileName
{
    public static function nameField() : string
    {
        return "name";
    }
    public static function newnameField() : string
    {
        return "newname";
    }

    public static function rules(...$extendRules) : array
    {
        return array("required", ...$extendRules);
    }
}
