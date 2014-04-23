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
            parent::save($relationship);
        }
        else{
            throw new ErrorException ('This object already saved, use update!');
        }

    }
} 