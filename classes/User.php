<?php

/**
 * @author Sergey Dashko
 * @version 1.0
 * @created 19-Apr-2014 10:12:53 PM
 */
class User implements MongoMappable
{

	private $Email;
	private $Uid;
	private $Name;
	private $Last_login_date;
	private $Registered_at;
	public $m_Object;

	/**
	 * 
	 * @param id
	 */
	public function __construct($id = null)
	{
	}

    function injectMapper(MongoMapper $mapper)
    {

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
	}

	public function save()
	{
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
		return $this->getRegistered_at;
	}

	/**
	 * 
	 * @param data
	 */
	public function setRegistered_at($data)
	{
		$this->getRegistered_at = $data;
	}

	public function __toString()
	{
        return 'User';
	}

}
?>