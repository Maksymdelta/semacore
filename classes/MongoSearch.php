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
        if(count($result)>0)
            return $result;
        else
            return false;
    }


    /**
     * @param $id * of object to remove from result
     * @param MongoMappable $mappable
     * @param array $filter
     * @param array $options
     * @return array|bool
     */
    function ajaxSearch($id=null,MongoMappable $mappable,array $filter=null,array $options=null)
    {
            $objType=$mappable->__toString();
            $obj=new DB\Mongo\Mapper(self::$db,$objType);
            $objList=$obj->select(array('name','_id'),$filter,$options);
            if(count($objList)>0)
                {
                    array_walk($objList,array($this, 'castObject'));
                    $result=array();
                    foreach($objList as $obj)
                    {
                        if($obj['_id']!=$id)$result[]=$obj;
                    }
                    return $result;
                }
            else
                return false;
    }

    private function castObject(&$mongoobj,$key=null)
    {
        $mongoobj=$mongoobj->cast();
        $mongoobj['_id']=(string)$mongoobj['_id'];
    }


    /**
     * return objects sorted by creation time
     * @param array $mappableArray Array of MongoMappable objects to find
     * @param array $filter
     * @param array $options
     * @return array|bool
     */
    function searchSortedByCreation(array $mappableArray,array $filter=null,array $options=null)
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
                    if($obj->getUid())$result[$obj->getCreated_at()]=$obj;
                }
            }
        }
        if(count($result)>0)
        {
            krsort($result);
            return $result;
        }
        else
            return false;
    }


    /**
     * @param array $mappableArray Array of MongoMappable objects to find
     * @param array $filter
     * @param array $options
     * @return array|bool
     */
    function count(array $mappableArray,array $filter=null,array $options=null)
    {
        $result=0;
        foreach($mappableArray as $mappable)
        {
            $objType=$mappable->__toString();
            $obj=new DB\Mongo\Mapper(self::$db,$objType);
            $objListCount=$obj->count($filter,$options);
            $result+=$objListCount;
        }
        return $result;
    }
}