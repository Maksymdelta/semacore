<?php

namespace Admin\Controller;

use \Admin\Controller;
use \Admin\Model\ManagerMapper;

class Manager extends Controller {
    function listAll ($f3) {
        $mapper   = new ManagerMapper();
        $managers = $mapper->find();

        $f3->set('managers', $managers);
        $this->renderPage($f3, 'manager/list');
    }

    public function add($f3) {
        // @TODO validate input

        $manager = new ManagerMapper();
        $manager->email       = $f3->get('POST.email');
        $manager->password    = $f3->get('POST.password');
        $manager->contacts    = $f3->get('POST.contacts');
        $manager->description = $f3->get('POST.description');

        $manager->save();

        $this->renderJSON(array('status' => 1));
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
