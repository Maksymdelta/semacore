<?php


class ManagerController extends baseController{
    function listObjects($f3)
    {
        $options=array('limit'=>1000, 'order'=>array('created_at'=>1));
        if($request=$f3->get('SESSION.request'))
        {
            $result=MongoSearch::getInstance()->search(array(new Entity(),new Relationship()),array('name'=>array('$in'=>[new \MongoRegex('/'.$request.'/i'),'$exists'=>true])),$options);

        }
        else{
            $result=MongoSearch::getInstance()->searchSortedByCreation(array(new Entity(),new Relationship()),null,$options);
        }
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

    function addRelationship($f3)
    {

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

    }

    function deleteEntity($f3)
    {
        if($f3->get('PARAMS.id'))
        {
            if(false!=$ent=new Entity($f3->get('PARAMS.id')))
            $ent->delete();
        }
        $f3->reroute('@list');
    }

    function deleteRelationship($f3)
    {

    }

} 