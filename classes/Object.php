<?php


/**
 * @author Sergey Dashko
 * @version 1.0
 * @created 19-Apr-2014 10:12:53 PM
 */
abstract class Object implements MongoMappable,NeoMappable
{

	private $Info_bits;
    private $Info_bits_ids;
	private $Type;
	private $Uid = null;
	private $Created_at;
	private $Created_by;
    private $Created_by_id;
	private $Name;
	private $MongoMapper;
    protected $Neo4jMapper;
    protected $Neo4jObject;

	/**
	 * 
	 * @param id
	 */
	public function __construct($id = null)
	{
        $this->MongoMapper=new MongoMapper();
        $this->injectMapper($this->MongoMapper);
        $this->injectNeo4jMapper($this->Neo4jMapper);
        if($id!=null){
            $this->load($id);
        }
	}

    function setNeo4jObject(Everyman\Neo4j\PropertyContainer $obj)
    {
        $this->Neo4jObject=$obj;
    }
    function getNeo4jObject()
    {
        return $this->Neo4jObject;
    }

    private function load($id)
	{
       if($this->MongoMapper->load($this,$id))
       {
           $this->setUid($id);
           if($this->Created_by_id!=null)$this->setCreated_by(new User($this->Created_by_id));
           return true;
       }
        return false;
	}

	public function save()
	{
        $this->Created_at=time();
        $this->setInfobits_ids();
        if(!$this->MongoMapper->save($this))return false;
        try{
            $this->Neo4jMapper->save($this);
        }
        catch(Exception $e){
            $this->MongoMapper->delete($this,$this->getUid());
            return false;
        }
        return true;
	}

    private function setInfobits_ids()
    {
        if(null!=$infobits=$this->getInfo_bits())
        {
            $result=array();
            foreach($infobits as $infobit)
            {
                if($infobit->getUid()==null)$infobit->save();
                $result[]=$infobit->getUid();
            }
            $this->Info_bits_ids=$result;
        }
    }



	public function update()
	{
        $this->MongoMapper->update($this);
	}

    function injectMapper(MongoMapper $mapper)
    {

        $mapper->attach($this->Name,'name');
        $mapper->attach($this->Created_at,'created_at');
        $mapper->attach($this->Created_by_id,'created_by_id');
        $mapper->attach($this->Info_bits_ids,'info_bits_ids');

    }

    function injectNeo4jMapper(Neo4jMapper $mapper)
    {
        $mapper->attach($this->Uid,'Uid');
        $mapper->attach($this->Type,'type');
        $mapper->attach($this->Name,'name');
    }

	public function getInfo_bits()
	{
        if($this->Info_bits==null&&$this->Info_bits_ids!=null)
        {
            foreach($this->Info_bits_ids as $id)
            {
                $this->Info_bits[]=new InfoBit($id);
            }
        }
		return $this->Info_bits;
	}

	/**
	 * 
	 * @param $Info_bit
	 */
	public function addInfo_bits(InfoBit $Info_bit)
	{
		$this->Info_bits[] = $Info_bit;
	}

	public function getObjType()
	{
		return $this->Type;
	}

	/**
	 * 
	 * @param type
	 */
	public function setType($type)
	{
		$this->Type = $type;
	}

	public function getUid()
	{
		return $this->Uid;
	}

	/**
	 * 
	 * @param id
	 */
    public function setUid($id)
	{
		$this->Uid = $id;
	}

	public function getCreated_by()
	{
		return $this->Created_by;
	}

	/**
	 * 
	 * @param User
	 */
	public function setCreated_by(User $User)
	{
		$this->Created_by = $User;
        $this->Created_by_id=$User->getUid();
	}

	public function getCreated_at()
	{
		return $this->Created_at;
	}

	/**
	 * 
	 * @param data
	 */
	public function setCreated_at($data)
	{
		$this->Created_at = $data;
	}

	public function getName()
	{
		return $this->Name;
	}

	/**
	 * 
	 * @param Name
	 */
	public function setName($Name)
	{
		$this->Name = $Name;
	}

	protected function getRepository()
	{
		return $this->Repository;
	}

	/**
	 * 
	 * @param repository
	 */
	protected function setRepository(MongoMapper $repository)
	{
		$this->Repository = $repository;
	}

	abstract function __toString();


}
?>