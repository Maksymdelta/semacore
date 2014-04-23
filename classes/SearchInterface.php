<?php


interface SearchInterface {
    function search(MongoMappable $object,array $filter);
    function getList(MongoMappable $object,$options);
} 