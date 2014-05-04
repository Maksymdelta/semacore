<?php





/**
 * @author Sergey Dashko
 * @version 1.0
 * @created 19-Apr-2014 10:12:53 PM
 */
class MongoMapper
{

    protected $attached = array();
    protected $db;
    protected $server;

  function  __construct()
  {
      if(!F3::get('mongoserver')||!F3::get('mongodb'))throw new Exception('No data source provided!');
      $this->server=F3::get('mongoserver');
      $this->db=new DB\Mongo($this->server,F3::get('mongodb'));
  }

	/**
	 * 
	 * @param Mappable
     * @param $id
	 */
	public function load(MongoMappable $mappable,$id)
	{
        $obj=new DB\Mongo\Mapper($this->db,$mappable->__toString());
        $obj->load(array('_id'=>new \MongoId($id)));
        if($obj->dry())return false;
        $this->fromDataStore($obj->cast());
        return true;
	}


    /**
     *
     * @param Mappable
     * @param $id
     */
    public function delete(MongoMappable $mappable,$id)
    {
        $obj=new DB\Mongo\Mapper($this->db,$mappable->__toString());
        $obj->load(array('_id'=>new \MongoId($id)));
        if($obj->dry())return false;
        return $obj->erase();
    }

	public function save(MongoMappable $mappable)
	{
        $obj=new DB\Mongo\Mapper($this->db,$mappable->__toString());
        foreach($this->fromDomainObject() as $fieldname=>$value)
        {
            if($value!=null)$obj->$fieldname=$value;
        }
        $result=$obj->save();
        if(isset($result['_id']))
        {
            $id=(string)($result['_id']);
            $mappable->setUid($id);
            return true;
        }
        else{
            return false;
        }
	}

	public function update(MongoMappable $mappable)
	{
        $obj=new DB\Mongo\Mapper($this->db,$mappable->__toString());
        $obj->load(array('_id'=>new \MongoId($mappable->getUid())));
        if($obj->dry())return false;
        foreach($this->fromDomainObject() as $fieldname=>$value)
        {
            $obj->$fieldname=$value;
        }
        return $obj->save();
	}


    public function attach(&$ref, $fieldName) {
        $this->attached[$fieldName] = &$ref;
    }

    private function fromDataStore($data) {
        foreach ($data as $fieldName => $value) {
            if (array_key_exists($fieldName, $this->attached)) {
                $this->attached[$fieldName] = $value;
            }
        }
    }

    private function fromDomainObject() {
        $data = array();
        foreach ($this->attached as $fieldName => $value) {
            $data[$fieldName] = $value;
        }
        return $data;

}

}
?>