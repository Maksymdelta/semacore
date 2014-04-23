<?php

/**
 * @author Sergey Dashko
 * @version 1.0
 * @created 19-Apr-2014 10:12:53 PM
 */
interface MongoMappable
{

    public function injectMapper(MongoMapper $mapper);
	/**
	 * 
	 * @param id
	 */
	public function __construct($id = null);

	public function save();

	public function update();

	public function getUid();


	/**
	 * 
	 * @param id
	 */
	public function setUid($id);

	public function __toString();

}
?>