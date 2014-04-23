<?php


class InfoBit implements MongoMappable{

    private $Created_by;
    private $Created_at;
    private $Uid;
    private $Key;
    private $Value;
    private $MongoMapper;
    private $Created_by_id;

    /**
     *
     * @param id
     */
    public function __construct($id = null)
    {
        $this->MongoMapper=new MongoMapper();
        $this->injectMapper($this->MongoMapper);
        if($id!=null){
            $this->load($id);
        }
    }

    public function __toString()
    {
        return 'InfoBit';
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
        if(!$this->MongoMapper->save($this))return false;
        return true;
    }

    public function update()
    {
        $this->MongoMapper->update($this);
    }

    function injectMapper(MongoMapper $mapper)
    {

        $mapper->attach($this->Key,'key');
        $mapper->attach($this->Created_at,'created_at');
        $mapper->attach($this->Value,'value');
        $mapper->attach($this->Created_by_id,'created_by_id');

    }

    public function getKey()
    {
        return $this->Key;
    }

    /**
     *
     * @param $key
     */
    public function setKey($key)
    {
        $this->Key = $key;
    }

    public function getValue()
    {
        return $this->Value;
    }

    /**
     *
     * @param $value
     */
    public function setValue($value)
    {
        $this->Value = $value;
    }


    function getUid()
    {
        return $this->Uid;
    }

    function setUid($id)
    {
        $this->Uid=$id;
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

} 