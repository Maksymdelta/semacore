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
           try{
               $this->Neo4jMapper->load($this,$id);
           }
           catch(Exception $e){
               return false;
           }
           return true;
       }
        return false;
	}

    public function delete()
    {
        if($this->getUid())
        {
            if($this->getInfo_bits())
            {
                foreach($this->getInfo_bits() as $infobit)
                {
                    $infobit->delete();
                }
            }
            $this->Neo4jMapper->delete($this,$this->getUid());
            $this->MongoMapper->delete($this,$this->getUid());
        }

    }

	public function save()
	{
        if($this->getUid())return $this->update();
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
        $this->Neo4jMapper->update($this);
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
        $this->loadInfo_bits();
		return $this->Info_bits;
	}

    private function loadInfo_bits()
    {
        if($this->Info_bits==null&&$this->Info_bits_ids!=null)
        {
            foreach($this->Info_bits_ids as $id)
            {
                $this->Info_bits[]=new InfoBit($id);
            }
        }
    }

	/**
	 *
	 * @param $Info_bit
	 */
	public function addInfo_bit(InfoBit $Info_bit)
	{
        $this->loadInfo_bits();
        if($Info_bit->getUid()==null)$Info_bit->save();
		$this->Info_bits[] = $Info_bit;
        $this->Info_bits_ids[]=$Info_bit->getUid();
	}

    /**
     * @param array $Info_bits array of Info_bit objects
     */
    public function setInfo_bits(array $Info_bits)
    {
        $this->Info_bits=$Info_bits;
        $ids=array();
        foreach($Info_bits as $infobit)
        {
            if($infobit->getUid()==null)$infobit->save();
            $ids[]=$infobit->getUid();
        }
        $this->Info_bits_ids=$ids;
    }

    public function deleteInfo_bit(InfoBit $infoBit)
    {
        $this->Info_bits = array_diff($this->getInfo_bits(), array($infoBit));
        $this->Info_bits_ids = array_diff($this->Info_bits_ids, array($infoBit->getUid()));
        $infoBit->delete();
    }

	public function getObjType()
	{
        if($this->Type==null&&$this->Neo4jObject!=null)$this->Type=$this->Neo4jObject->getProperty('type');
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