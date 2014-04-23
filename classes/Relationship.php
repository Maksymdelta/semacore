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

	public function getStart_node()
	{
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
		return $this->End_node;
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

}
?>