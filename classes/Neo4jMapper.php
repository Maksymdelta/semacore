<?php

use Everyman\Neo4j\Client,
    Everyman\Neo4j\Transport,
    Everyman\Neo4j\Node,
    Everyman\Neo4j\Relationship;



/**
 * @author Sergey Dashko
 * @version 1.0
 * @created 19-Apr-2014 10:12:53 PM
 */
class Neo4jMapper
{

    protected $attached = array();
    protected $client;


    function  __construct()
    {
        if(!F3::get('neo4jserver')||!F3::get('neo4jport'))throw new Exception('No data source provided!');
        $this->client = new Client(F3::get('neo4jserver'), F3::get('neo4jport'));
    }
	/**
	 * 
	 * @param Mappable
	 */
	public function load(NeoMappable $Mappable)
	{
	}

	public function save(NeoMappable $Mappable)
	{
       $object=$Mappable->getNeo4jObject();
            foreach($this->fromDomainObject() as $fieldname=>$value)
            {
                if($value!=null)$object->setProperty($fieldname, $value);
            }
            $object->save();
	}

	public function update()
	{
	}


    public function attach(&$ref, $fieldName) {
        $this->attached[$fieldName] = &$ref;
    }

    public function fromDataStore($data) {
        foreach ($data as $fieldName => $value) {
            if (array_key_exists($fieldName, $this->attached)) {
                $this->attached[$fieldName] = $value;
            }
        }
    }

    public function fromDomainObject() {
        $data = array();
        foreach ($this->attached as $fieldName => $value) {
            $data[$fieldName] = $value;
        }
        return $data;

}

}
?>