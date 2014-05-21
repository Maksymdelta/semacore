<?php

/**
 * @author Sergey Dashko
 * @version 1.0
 * @created 19-Apr-2014 10:12:53 PM
 */
class Manager implements MongoMappable
{

	private $Email;
	private $Uid;
	private $Name;
	private $Last_login_date;
	private $Registered_at;
    private $MongoMapper;

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

    function injectMapper(MongoMapper $mapper)
    {
        $mapper->attach($this->Email,'email');
        $mapper->attach($this->Registered_at,'registered_at');
        $mapper->attach($this->Last_login_date,'last_login_date');
        $mapper->attach($this->Name,'name');
    }

    function delete()
    {
        if($this->getUid())
        {
            $this->MongoMapper->delete($this,$this->getUid());
        }
    }

	public function getEmail()
	{
		return $this->Email;
	}

	/**
	 * 
	 * @param id
	 */
    public function load($id)
	{
        if($this->MongoMapper->load($this,$id))
        {
            return true;
        }
        return false;
	}

	public function save()
	{
        if($this->getUid())return $this->update();
        $this->Registered_at=time();
        if(!$this->MongoMapper->save($this))return false;
        return true;
	}

	/**
	 * 
	 * @param email
	 */
	public function setEmail($email)
	{
		$this->Email = $email;
	}

	public function getName()
	{
		return $this->Name;
	}

	public function update()
	{
        $this->MongoMapper->update($this);
	}

	/**
	 * 
	 * @param name
	 */
	public function setName($name)
	{
		$this->Name = $name;
	}

	public function getLast_login_date()
	{
		return $this->Last_login_date;
	}

	public function getUid()
	{
		return $this->Uid;
	}

	/**
	 * 
	 * @param login
	 */
	public function setLast_login_date($login)
	{
		$this->Last_login_date = $login;
	}

	/**
	 * 
	 * @param id
	 */
    public function setUid($id)
	{
		$this->Uid = $id;
	}

	public function getRegistered_at()
	{
		return $this->Registered_at;
	}

	/**
	 * 
	 * @param data
	 */
	public function setRegistered_at($data)
	{
		$this->Registered_at = $data;
	}

	public function __toString()
	{
        return 'User';
	}

}
?>