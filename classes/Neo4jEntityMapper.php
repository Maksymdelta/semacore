<?php


class Neo4jEntityMapper extends Neo4jMapper{

    public function save(NeoMappable $Mappable)
    {
        if($entity->getNeo4jObject()==null)
        {
            $entity->setNeo4jObject($this->client->makeNode());

        }
        parent::save($entity);

    }


    function  __construct()
    {
        parent::__construct();
        $this->index = new Everyman\Neo4j\Index\NodeIndex($this->client, 'entity');
    }

    function delete(Entity $mappable)
    {
        if(!$mappable->getNeo4jObject())return false;
        if($mappable->getRelationships()){
            foreach($mappable->getRelationships() as $rel)
            {
                $rel->delete();
            }
        }
        try{
            $mappable->getNeo4jObject()->delete();
        }
        catch(Exception $e)
        {
            return false;
        }
        return true;
    }


} 