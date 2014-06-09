<?php

use \Admin\Controller;
use \Admin\Model\ManagerMapper;

class AdminManager extends Controller {
    function listAll ($f3) {
        $mapper   = new ManagerMapper();
        $managers = $mapper->find();

        $f3->set('managers', $managers);
        $this->renderPage($f3, 'manager/list');
    }

    public function doAddManager($f3) {
        if(!$f3->get('POST.mg-d-email')||!$f3->get('POST.mg-d-password')||!$f3->get('POST.mg-d-contacts'))
        {
			Flash::setflash('Заповніть будь ласка всі поля!');
			Flash::flashPost();
			$this->renderPage($f3, 'manager/add');
			return;
		}
	
    	$manager = new Manager();
		$manager->setEmail($f3->get('POST.mg-d-email'));
		$manager->setPassword($f3->get('POST.mg-d-password'));
		$manager->setContacts($f3->get('POST.mg-d-contacts'));
		$manager->setDescription($f3->get('POST.mg-d-description'));

		$manager->save();
	
		Flash::setflash('Додано нового менеджера!');
		$f3->reroute('@admin_managers_list');
		
    }
	
	public function addManager($f3) {
		// $f3->set('head','../'.$f3->get('UI').'list_head.html');
        // echo Template::instance($f3)->render('../'.$f3->get('UI').'list.html');
		
		
		// $uiPath = $f3->get('UI');

        // $f3->set('content', "../${uiPath}admin/pages/${pageName}.html");
        // echo \Template::instance($f3)->render("../${uiPath}admin/main.html", 'text/html');
		
		
        $this->renderPage($f3, 'manager/add');
		//echo Template::instance($f3)->render('../'.$f3->get('UI').'addentity.html');
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

	function editManager($f3)
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
	
    public function show($f3) {
        $_id = $f3->get('PARAMS.managerID');
        
        $mapper  = new ManagerMapper();
        $manager = $mapper->load(array('_id'=> new \MongoId($_id)));

        $this->renderJSON($f3, array(
            'email'       => $manager->email,
            'invite'      => $manager->invite,
            'contacts'    => $manager->contacts,
            'description' => $manager->description,
            'createdAt'   => $manager->createdAt,
            'updatedAt'   => $manager->updatedAt
        ));
    }
}
