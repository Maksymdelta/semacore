<?php


class Neo4jRelationshipMapper extends Neo4jMapper{
    function save(NeoMappable $relationship)
    {
        if($relationship->getNeo4jObject()==null)
        {
            $startNode=$relationship->getStart_node();
            if($startNode->getNeo4jObject()==null)$startNode->save();
            $endNode=$relationship->getEnd_node();
            if($endNode->getNeo4jObject()==null)$endNode->save();
            $startNodeNeoObj=$startNode->getNeo4jObject();
            $endNodeNeoObj=$endNode->getNeo4jObject();
            $relation = $this->client->makeRelationship();
            $relation->setStartNode($startNodeNeoObj)
                ->setEndNode($endNodeNeoObj)
                ->setType($relationship->getObjType())
                ->save();
            $relationship->setNeo4jObject($relation);
        }
        parent::save($relationship);

    }

    function update(NeoMappable $relationship)
    {
        if($relationship->getNeo4jObject()==null)
        {
            $relationship->save();
            return;
        }
        $startNode=$relationship->getStart_node();
        if($startNode->getNeo4jObject()==null)$startNode->save();
        $endNode=$relationship->getEnd_node();
        if($endNode->getNeo4jObject()==null)$endNode->save();
        $startNodeNeoObj=$startNode->getNeo4jObject();
        $endNodeNeoObj=$endNode->getNeo4jObject();
        $relation = $relationship->getNeo4jObject();
        $relation->setStartNode($startNodeNeoObj)
            ->setEndNode($endNodeNeoObj)
            ->setType($relationship->getObjType())
            ->save();
        parent::update($relationship);
    }


    function delete(Relationship $mappable)
    {
        if(!$mappable->getNeo4jObject())return false;
        try{
            $mappable->getNeo4jObject()->delete();
        }
        catch(Exception $e)
        {
            return false;
        }
        return true;
    }

    function  __construct()
    {
        parent::__construct();
        $this->index = new Everyman\Neo4j\Index\RelationshipIndex($this->client, 'relationship');
    }
} 