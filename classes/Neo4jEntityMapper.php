<?php


class Neo4jEntityMapper extends Neo4jMapper{

    public function save(NeoMappable $entity)
    {
        if($entity->getNeo4jObject()==null)
        {
            $entity->setNeo4jObject($this->client->makeNode());

        }
        parent::save($entity);

    }



} 