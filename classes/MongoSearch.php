<?php


class MongoSearch {

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

    /**
     * @param array $mappableArray Array of MongoMappable objects to find
     * @param array $filter
     * @param array $options
     * @return array|bool
     */
    function search(array $mappableArray,array $filter=null,array $options=null)
    {
        $result=array();
        foreach($mappableArray as $mappable)
        {
            $objType=$mappable->__toString();
            $obj=new DB\Mongo\Mapper(self::$db,$objType);
            $objList=$obj->find($filter,$options);
            if(count($objList)>0)
            {
                foreach($objList as $object)
                {
                    $obj=new $objType((string)$object->_id);
                    if($obj->getUid())$result[]=$obj;
                }
            }
        }
        return $result;

    }
}