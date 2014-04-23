<?php

/**
 * @author Sergey Dashko
 * @version 1.0
 * @created 19-Apr-2014 10:12:53 PM
 */
class Entity extends Object
{

	private $Classes;
    private $Description;
    protected $node;

	public function getClasses()
	{
		return $this->Classes;
	}


	public function setClass(array $classes)
	{
		$this->Classes = $classes;
	}

	public function __toString()
	{
        return 'Entity';
	}

    function setDescription($text)
    {
        $this->Description=$text;
    }

    function getDescription()
    {
        return $this->Description;
    }

    function injectNeo4jMapper(Neo4jMapper $entityMapper)
    {
        $entityMapper->attach($this->Classes,'Class');
        parent::injectNeo4jMapper($entityMapper);
    }

    function injectMapper(MongoMapper $mapper)
    {
        $mapper->attach($this->Classes,'Class');
        $mapper->attach($this->Description,'description');
        parent::injectMapper($mapper);
    }

    function __construct($id=null)
    {
        $this->Neo4jMapper=new Neo4jEntityMapper();
        parent::__construct($id);
    }


}
?>