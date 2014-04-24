<?php

/**
 * @author Sergey Dashko
 * @version 1.0
 * @created 19-Apr-2014 10:12:53 PM
 */
interface NeoMappable
{

    public function injectNeo4jMapper(Neo4jMapper $mapper);
	/**
	 * 
	 * @param id
	 */
	public function __construct($id = null);

	public function save();

	public function update();

	public function getUid();

    public function delete();

    public function getNeo4jObject();
    public function setNeo4jObject(Everyman\Neo4j\PropertyContainer $obj);

	/**
	 * 
	 * @param id
	 */
	public function setUid($id);

	public function __toString();

}
?>