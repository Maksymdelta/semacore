<?php
require_once ('Object.php');


/**
 * @author Sergey Dashko
 * @version 1.0
 * @created 19-Apr-2014 10:12:53 PM
 */
class Relationship extends Object
{

	private $Start_node;
	private $End_node;
    private $Start_node_id;
    private $End_node_id;

	public function getStart_node()
	{
        $this->loadNode($this->Start_node,$this->Start_node_id);
		return $this->Start_node;
	}

	/**
	 * 
	 * @param node
	 */
	public function setStart_node(Entity $node)
	{
		$this->Start_node = $node;
	}

	public function getEnd_node()
	{
        $this->loadNode($this->End_node,$this->End_node_id);
		return $this->End_node;
	}

    private function loadNode(&$node,$id=null)
    {
        if($node==null&&$id!=null)$node=new Entity($id);
    }

	/**
	 * 
	 * @param node
	 */
	public function setEnd_node(Entity $node)
	{
		$this->End_node = $node;
	}

	public function __toString()
	{
        return 'Relationship';
	}

    function __construct($id=null)
    {
        $this->Neo4jMapper=new Neo4jRelationshipMapper();
        parent::__construct($id);
    }

    function injectNeo4jMapper(Neo4jMapper $relationMapper)
    {
        parent::injectNeo4jMapper($relationMapper);
    }

    function injectMapper(MongoMapper $mapper)
    {
        $mapper->attach($this->Start_node_id,'start_node');
        $mapper->attach($this->End_node_id,'end_node');
        parent::injectMapper($mapper);
    }

    function save()
    {
        if($this->getStart_node()==null||$this->getEnd_node()==null)return false;
        $this->Name=$this->getStart_node()->getName().' - '.$this->getEnd_node()->getName();
        $this->initNodes();
        parent::save();
    }

    function getOtherSide(Entity $entity)
    {
        if($this->getStart_node()->getUid()==$entity->getUid())return $this->getEnd_node();
        return $this->getStart_node();
    }

    function update()
    {
        if($this->getStart_node()==null||$this->getEnd_node()==null)return false;
        $this->Name=$this->getStart_node()->getName().' - '.$this->getEnd_node()->getName();
        $this->initNodes();
        return parent::update();
    }


    private function initNodes()
    {
        if($this->getStart_node()->getUid()==null)$this->getStart_node()->save();
        if($this->getEnd_node()->getUid()==null)$this->getEnd_node()->save();
        $this->Start_node_id=$this->getStart_node()->getUid();
        $this->End_node_id=$this->getEnd_node()->getUid();
    }


}
?>