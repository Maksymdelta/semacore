<?php


class ManagerController extends baseController{
    function listObjects($f3)
    {
        $options=array('limit'=>1000, 'order'=>array('created_at'=>1));
        if($request=$f3->get('SESSION.request'))
        {
            $result=MongoSearch::getInstance()->search(array(new Entity(),new Relationship()),array('name'=>array('$in'=>array_values([new \MongoRegex('/'.$request.'/i'),'$exists'=>true]))),$options);

        }
        else{
            $result=MongoSearch::getInstance()->searchSortedByCreation(array(new Entity(),new Relationship()),null,$options);
        }
        if($result!=false)
        {
            $output=array();
            foreach($result as $obj)
            {
                $output["aaData"][]=array(
                    "DT_RowId"=>(string)$obj.'|'.$obj->getUid(),
                    (string)$obj=="Entity"?'Сутність':'Зв\'язок',
                    $obj->getName(),
                    $obj->getObjType(),
                    date('m.d.y h:m',$obj->getCreated_at())
                );
            }
            if(isset($output["aaData"]))$f3->set('aaData',json_encode( $output["aaData"] ));
        }

        $f3->set('head','../'.$f3->get('UI').'list_head.html');
        echo Template::instance($f3)->render('../'.$f3->get('UI').'list.html');
        $f3->clear('SESSION.request');
    }


    function listSearch($f3)
    {
        if(!$f3->get('POST.request'))$f3->reroute('@list');
        $f3->set('SESSION.request',$f3->get('POST.request'));
        $f3->reroute('@list');
    }

    function addEntity($f3)
    {
        $f3->set('head','../'.$f3->get('UI').'addentity_head.html');
        $f3->set('types',EntityType::getTypes());
        $f3->set('classes',EntityClass::getClasses());
        echo Template::instance($f3)->render('../'.$f3->get('UI').'addentity.html');
    }

    function doAddEntity($f3)
    {
        if(!$f3->get('POST.name')||$f3->get('POST.name')==''||$f3->get('POST.type')=='none'||!$f3->get('POST.class')||!$f3->get('POST.description')){
            Flash::setflash('Заповніть будь ласка всі поля!');
            Flash::flashPost();
            $f3->reroute('@entityadd');
        }
        $entity=new Entity();
        $entity->setName($f3->get('POST.name'));
        $entity->setType($f3->get('POST.type'));
        $entity->setClass($f3->get('POST.class'));
        $entity->setDescription($f3->get('POST.description'));
        $entity->save();
        $f3->reroute('@list');
    }

    function addRelationshipToEntity($f3)
    {
        if($f3->get('PARAMS.entityid'))
        {
            $ent=new Entity($f3->get('PARAMS.entityid'));
            $f3->set('entity', $ent);
            $f3->set('relationtypes', RelationType::getTypes());
            $f3->set('head','../'.$f3->get('UI').'entityrelations_head.html');
            echo Template::instance($f3)->render('../'.$f3->get('UI').'entityrelations.html');
        }
    }


    function changeRelationship($f3)
    {
        if(!$f3->get('POST.entity1')||!$f3->get('POST.entity2')||!$f3->get('POST.relation'))
        {
            echo 'false';
            return;
        }

        $ent=new Entity($f3->get('POST.entity1'));
        $ent2=new Entity($f3->get('POST.entity2'));
        $rel=new Relationship($f3->get('POST.relation'));
        $rel->setStart_node($ent);
        $rel->setEnd_node($ent2);
        if($rel->update())
        {
            echo 'success';
            return;
        }
        echo 'false';
    }

    function replaceEntity($f3)
    {
        if(!$f3->get('POST.entity1')||!$f3->get('POST.entity2')||!$f3->get('POST.relation'))
        {
            echo 'false';
            return;
        }

        $currentEnt=new Entity($f3->get('POST.entity1'));
        $newEnt=new Entity($f3->get('POST.entity2'));
        $rel=new Relationship($f3->get('POST.relation'));
        $rel->replaceEntity($currentEnt,$newEnt);
        if($rel->update())
        {
            echo 'success';
            return;
        }
        echo 'false';
    }

    function relationshipSetType($f3)
    {
        if(!$f3->get('POST.type')||!$f3->get('POST.relation'))
        {
            echo 'false';
            return;
        }
        $rel=new Relationship($f3->get('POST.relation'));
        $rel->setType($f3->get('POST.type'));
        $rel->save();
        echo 'success';
    }

    function queryEntity($f3)
    {
        if(!$f3->get('PARAMS.query'))
        {
            echo json_encode('false');
            return;
        }
        $f3->get('PARAMS.entityid')?$entid=$f3->get('PARAMS.entityid'):$entid=null;
        $options=array('limit'=>50, 'order'=>array('created_at'=>1));
        if(false!=$result=MongoSearch::getInstance()->ajaxSearch($entid,new Entity(),array('name'=>array('$in'=>array_values([new \MongoRegex('/'.$f3->get('PARAMS.query').'/i'),'$exists'=>true]))),$options))
            echo json_encode($result);
        else
            echo json_encode('false');
    }

    function doAddRelationshipToEntity($f3)
    {
        if(!$f3->get('POST.entity1')||!$f3->get('POST.entity2')||!$f3->get('POST.type'))
        {
            $f3->reroute('@list');
        }
        $ent1=new Entity($f3->get('POST.entity1'));
        $ent2=new Entity($f3->get('POST.entity2'));
        if($ent1->getUid()==null||$ent2->getUid()==null)
        {
            $f3->reroute('@list');
        }
        $rel=new Relationship();
        $rel->setStart_node($ent1);
        $rel->setEnd_node($ent2);
        $rel->setType($f3->get('POST.type'));
        $rel->save();
        $f3->reroute('@relationshipadd(@entityid='.$f3->get('POST.entity1').')');
    }

    function addRelationship($f3)
    {
        $f3->set('types',RelationType::getTypes());
        $f3->set('head','../'.$f3->get('UI').'editrelationship_head.html');
        echo Template::instance($f3)->render('../'.$f3->get('UI').'addrelationship.html');
    }

    function doAddRelationship($f3)
    {
        if(!$f3->get('POST.startEntity')||!$f3->get('POST.endEntity')||!$f3->get('POST.type'))
        {
            Flash::setflash('Виберіть будь ласка 2 об\'єкти');
            $f3->reroute('@newrelationship');
        }
        $startEnt=new Entity($f3->get('POST.startEntity'));
        $endEnt=new Entity($f3->get('POST.endEntity'));
        $rel=new Relationship();
        $rel->setStart_node($startEnt);
        $rel->setEnd_node($endEnt);
        $rel->setType($f3->get('POST.type'));
        $rel->save();
        $f3->reroute('@relationshipedit(@id='.$rel->getUid().')');
    }

    function editEntity($f3)
    {
        if(!$f3->get('PARAMS.id'))$f3->reroute('@list');
        if(false!=$ent=new Entity($f3->get('PARAMS.id')))
        {
            $f3->set('entity',$ent);
            $f3->set('head','../'.$f3->get('UI').'editentity_head.html');
            $f3->set('types',EntityType::getTypes());
            $f3->set('classes',EntityClass::getClasses());
            echo Template::instance($f3)->render('../'.$f3->get('UI').'editentity.html');
            return;
        }
        $f3->reroute('@list');
    }

    function addInfobitToEntity($f3)
    {
        if(!$f3->get('POST.uid')||!$f3->get('POST.infobittitle')||!$f3->get('POST.infobit'))
        {
            $f3->reroute('@list');
        }
        if(false!=$entity=new Entity($f3->get('POST.uid')))
        {
            $infoBit=new InfoBit();
            $infoBit->setKey($f3->get('POST.infobittitle'));
            $infoBit->setValue($f3->get('POST.infobit'));
            $entity->addInfo_bit($infoBit);
            $entity->save();
        }

        $f3->reroute('@entityedit(@id='.$f3->get('POST.uid').')');
    }

    function doEditEntity($f3)
    {
        if($f3->get('POST.uid')&&$f3->get('POST.description')&&$f3->get('POST.class')&&$f3->get('POST.type')&&$f3->get('POST.name'))
        {
            if(false!=$ent=new Entity($f3->get('POST.uid')))
            {
                $ent->setName($f3->get('POST.name'));
                $ent->setType($f3->get('POST.type'));
                $ent->setClass($f3->get('POST.class'));
                $ent->setDescription($f3->get('POST.description'));

                if($f3->get('POST.infobittitle')&&$f3->get('POST.infobitvalue'))
                {
                    $titles=$f3->get('POST.infobittitle');
                    $values=$f3->get('POST.infobitvalue');
                    $infobits=array();
                    foreach($titles as $uid=>$title)
                    {
                        $infbit=new InfoBit($uid);
                        $infbit->setKey($title);
                        $infbit->setValue($values[$uid]);
                        $infbit->save();
                        $infobits[]=$infbit;
                    }
                    $ent->setInfo_bits($infobits);
                }
                $ent->update();
                $f3->reroute('@list');
            }
        }
        if($f3->get('POST.uid')){
            Flash::setflash('Заповніть будь ласка всі поля!');
            $f3->reroute('@entityedit(@id='.$f3->get('POST.uid').')');
        }
        $f3->reroute('@list');
    }


    function doEditRelationship($f3)
    {
        if($f3->get('POST.uid')&&$f3->get('POST.type'))
        {
            if(false!=$rel=new Relationship($f3->get('POST.uid')))
            {
                $rel->setType($f3->get('POST.type'));
                if($f3->get('POST.infobittitle')&&$f3->get('POST.infobitvalue'))
                {
                    $titles=$f3->get('POST.infobittitle');
                    $values=$f3->get('POST.infobitvalue');
                    $infobits=array();
                    foreach($titles as $uid=>$title)
                    {
                        $infbit=new InfoBit($uid);
                        $infbit->setKey($title);
                        $infbit->setValue($values[$uid]);
                        $infbit->save();
                        $infobits[]=$infbit;
                    }
                    $rel->setInfo_bits($infobits);
                }
                $rel->update();
                $f3->reroute('@list');
            }
        }
        if($f3->get('POST.uid')){
            Flash::setflash('Заповніть будь ласка всі поля!');
            $f3->reroute('@relationshipedit(@id='.$f3->get('POST.uid').')');
        }
        $f3->reroute('@list');
    }

    function deleteInfobitFromEntity($f3)
    {
        if(false!=$ent=new Entity($f3->get('PARAMS.entityid')))
        {
            if(false!=$infobit=new InfoBit($f3->get('PARAMS.infobitid')))
            {
                $ent->deleteInfo_bit($infobit);
                $ent->save();
                $f3->reroute('@entityedit(@id='.$f3->get('PARAMS.entityid').')');
            }
        }
        $f3->reroute('@list');
    }

    function editRelationship($f3)
    {
        if(!$f3->get('PARAMS.id'))
        {
            $f3->reroute('@list');
        }
        $rel=new Relationship($f3->get('PARAMS.id'));
        $f3->set('relationship',$rel);
        $f3->set('types',RelationType::getTypes());
        $f3->set('head','../'.$f3->get('UI').'editrelationship_head.html');
        echo Template::instance($f3)->render('../'.$f3->get('UI').'editrelationship.html');
    }

    function addInfobitToRelationship($f3)
    {
        if($f3->get('POST.uid')&&$f3->get('POST.infobittitle')&&$f3->get('POST.infobit'))
        {
            $relationship=new Relationship($f3->get('POST.uid'));
            $infobit=new InfoBit();
            $infobit->setKey($f3->get('POST.infobittitle'));
            $infobit->setValue($f3->get('POST.infobit'));
            $infobit->save();
            $relationship->addInfo_bit($infobit);
            $relationship->save();
        }
        $f3->reroute('@relationshipedit(@id='.$f3->get('POST.uid').')');
    }

    function deleteInfobitFromRelationship($f3)
    {
        if($f3->get('PARAMS.relid')&&$f3->get('PARAMS.infobitid'))
        {
            if(false!=$rel=new Relationship($f3->get('PARAMS.relid')))
            {
                if(false!=$infobit=new InfoBit($f3->get('PARAMS.infobitid')))
                {
                    $rel->deleteInfo_bit($infobit);
                    $rel->save();
                    $f3->reroute('@relationshipedit(@id='.$f3->get('PARAMS.relid').')');
                }
            }
        }
        $f3->reroute('@list');
    }

    function deleteEntity($f3)
    {
        if($f3->get('PARAMS.id'))
        {
            $ent=new Entity($f3->get('PARAMS.id'));
            $ent->delete();
        }
        $f3->reroute('@list');
    }

    function deleteRelationship($f3)
    {
        if($f3->get('PARAMS.id'))
        {
            $rel=new Relationship($f3->get('PARAMS.id'));
            $rel->delete();
        }
        $f3->get('PARAMS.entityid')?$f3->reroute('@relationshipadd(@entityid='.$f3->get('PARAMS.entityid').')'):$f3->reroute('@list');
    }

} 