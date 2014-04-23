<?php

/**
 * @author Sergey Dashko
 * @version 1.0
 * @created 19-Apr-2014 10:12:53 PM
 */
class EntityType
{

	private static $Types=array(
        'Людина',
        'Компанія',
        'Організація',
        'Документ',
        'Подія',
        'Об’єкт'
    );

	public static function getTypes()
	{
		return self::$Types;
	}

}
?>