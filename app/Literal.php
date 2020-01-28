<?php

namespace App;


class Literal
{
    public static function nameField() : string
    {
        return "name";
    }
    
    public static function newnameField() : string
    {
        return "newname";
    }

    public static function nameRules(...$extendRules) : array
    {
        return array("required", ...$extendRules);
    }
}
