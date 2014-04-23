<?php


class EntityClass {
    private static $Classes=array(
        'Держслужбовець',
        'Силовик',
        'Холдинг',
        'Акціонерне товариство',
        'Громадська Організація',
        'Договір',
        'Тендер',
        'Звіт',
        'Розслідування',
        'Анонс'
    );

    public static function getClasses()
    {
        return self::$Classes;
    }
} 