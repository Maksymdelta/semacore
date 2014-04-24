<?php


class ManagerController extends baseController{
    function listObjects($f3)
    {
        if($request=$f3->get('SESSION.request'))
        {
            $result=MongoSearch::getInstance()->search(array(new Entity(),new Relationship()),array('name'=>array('$in'=>[new \MongoRegex('/'.$request.'/i'),'$exists'=>true])));
            $f3->set('list',$result);
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
        $infobit=new InfoBit();
        $infobit->setKey($f3->get('POST.infobit'));
        $infobit->setValue($f3->get('POST.infobitvalue'));
        $entity->addInfo_bits($infobit);
        $entity->save();
        $f3->reroute('@entityadd');
    }

} 