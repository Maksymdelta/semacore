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
    private $Relationships;
    protected $node;

	public function getClasses()
	{
		return $this->Classes;
	}

    /**
     * Check if entity belong to class
     * @param $className
     * @return bool
     */

    public function belongToClass($className)
    {
       return in_array($className,$this->getClasses(),true);
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

    function getRelationships()
    {
        $this->loadRelationships();
        return $this->Relationships;
    }

    function update()
    {
        parent::update();
        $this->loadRelationships();
        foreach($this->getRelationships() as $rel)
        {
            $rel->update();
        }
    }

    private function loadRelationships()
    {
        if($this->Neo4jObject!=null&&$this->Relationships==null)
        {
            if($this->Neo4jObject->getRelationships()){
                foreach($this->Neo4jObject->getRelationships() as $rel)
                {
                    $this->Relationships[]=new Relationship($rel->getProperty('Uid'));
                }
            }
        }
    }

    function __construct($id=null)
    {
        $this->Neo4jMapper=new Neo4jEntityMapper();
        parent::__construct($id);
    }


}
?>