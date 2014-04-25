<?php

$f3=require('lib/base.php');

$f3->set('DEBUG',3);
if ((float)PCRE_VERSION<7.9)
	trigger_error('PCRE version is out of date');
$f3->set('AUTOLOAD','classes/;controllers/;lib');
$f3->set('CACHE', true);
require('lib/vendor/autoload.php');
$f3->config('config.ini');

$f3->route('GET @list: /manager/list','ManagerController->listObjects');
$f3->route('GET @ajaxlist: /manager/list [ajax]','ManagerController->ajaxListObjects');
$f3->route('POST @listsearch: /manager/search','ManagerController->listSearch');
$f3->route('GET @entityadd: /manager/entity/add','ManagerController->addEntity');
$f3->route('GET @relationshipadd: /manager/link/add','ManagerController->addRelationship');
$f3->route('GET @entityedit: /manager/editobject/entity/@id','ManagerController->editEntity');
$f3->route('GET @relationshipedit: /manager/editobject/relationship/@id','ManagerController->editRelationship');
$f3->route('GET @entitydelete: /manager/deleteobject/entity/@id','ManagerController->deleteEntity');
$f3->route('GET @relationshipdelete: /manager/deleteobject/relationship/@id','ManagerController->deleteRelationship');
$f3->route('POST @doaddentity: /manager/entity/add','ManagerController->doAddEntity');

$f3->run();
