<?php

/**
 * @author Sergey Dashko
 * @version 1.0
 * @created 19-Apr-2014 10:12:53 PM
 */
class RelationType
{

	private static $Types=array(
        'Власник',
        'Родина',
        'Пов\'язані',
        'Керує'
    );


	public static function getTypes()
	{
		return self::$Types;
	}

}
?>