<?php


class MongoSearch implements SearchInterface{

    protected static $db;
    protected static $server;
    private static $instance;

    private function  __construct()
    {
        if(!F3::get('mongoserver')||!F3::get('mongodb'))throw new Exception('No data source provided!');
        self::$server=F3::get('mongoserver');
        self::$db=new DB\Mongo(self::$server,F3::get('mongodb'));
    }

    public static function getInstance()
    {
        if ( is_null( self::$instance ) )
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function search(MongoMappable $object, array $filter)
    {

    }

    function getList(MongoMappable $mappable,$options=null)
    {
        $objType=$mappable->__toString();
        $obj=new DB\Mongo\Mapper(self::$db,$objType);
        $objList=$obj->find(null,$options);
        $result=array();
        if(count($objList)>0)
        {
            foreach($objList as $object)
            {
                $obj=new $objType((string)$object->_id);
                if($obj->getUid())$result[]=$obj;
            }
            return $result;
        }
        return false;
    }
}