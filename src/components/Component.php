<?php

namespace ssz\mobo\components;

class Component
{
    private static $instances = [];

    public static function __callStatic( $name, $args )
    {
        if ( !isset(self::$instances[ $name ] ) ) {
            $c = "\\ssz\\mobo\\components\\{$name}";
            self::$instances[ $name ] = new $c;
        }

        return self::$instances[ $name ];
    }
}
