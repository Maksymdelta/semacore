<?php


class ManagerController extends baseController{
    function listEntity($f3)
    {
        if($result=MongoSearch::getInstance()->getList(new Entity()))
        {
            $f3->set('list',$result);
        }
        echo Template::instance($f3)->render('../'.$f3->get('UI').'list.html');
    }

    function addEntity($f3)
    {
        $f3->set('head','../'.$f3->get('UI').'addentity_head.html');
        $f3->set('types',RelationType::getTypes());
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
        $infobit=new InfoBit();
        $infobit->setKey($f3->get('POST.infobit'));
        $infobit->setValue($f3->get('POST.infobitvalue'));
        $entity->addInfo_bits($infobit);
        $entity->save();
        $f3->reroute('@entityadd');
    }

} 