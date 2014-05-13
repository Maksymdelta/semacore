<?php

$f3=require('lib/base.php');

$f3->set('DEBUG',3);
if ((float)PCRE_VERSION<7.9)
 trigger_error('PCRE version is out of date');
$f3->set('AUTOLOAD','classes/;controllers/;lib');
$f3->set('CACHE', true);
require('lib/vendor/autoload.php');
$f3->config('config.ini');

\Template::instance()->extend('handlebars','Admin\Template\Helpers::handlebars');
$f3->set('mongo', new \DB\Mongo($f3->get('mongoserver'), $f3->get('mongodb')));

$f3->route('GET @list: /manager/list','ManagerController->listObjects');
$f3->route('POST @addinfobit: /manager/entity/addinfobit','ManagerController->addInfobitToEntity');
$f3->route('GET @deleteinfobit: /manager/entity/@entityid/deleteinfobit/@infobitid','ManagerController->deleteInfobitFromEntity');
$f3->route('POST @listsearch: /manager/search','ManagerController->listSearch');
$f3->route('GET @entityadd: /manager/entity/add','ManagerController->addEntity');
$f3->route('GET @relationshipadd: /manager/relationship/add/@entityid','ManagerController->addRelationship');
$f3->route('POST @doaddrelationship: /manager/relationship/add','ManagerController->doAddRelationship');
$f3->route('POST @changerelationship: /manager/relationship/change [ajax]','ManagerController->changeRelationship');
$f3->route('POST @relationshipsettype: /manager/relationship/settype [ajax]','ManagerController->relationshipSetType');
$f3->route('GET @entityedit: /manager/editobject/entity/@id','ManagerController->editEntity');
$f3->route('GET @entityquery: /manager/entity/@entityid/query/@query.json [ajax]','ManagerController->queryEntity');
$f3->route('POST @doeditentity: /manager/entity/edit','ManagerController->doEditEntity');
$f3->route('GET @relationshipedit: /manager/editobject/relationship/@id','ManagerController->editRelationship');
$f3->route('GET @entitydelete: /manager/deleteobject/entity/@id','ManagerController->deleteEntity');
$f3->route('GET @relationshipdelete: /manager/deleteobject/relationship/@id','ManagerController->deleteRelationship');
$f3->route('GET /manager/entity/@entityid/deleterelation/@id','ManagerController->deleteRelationship');
$f3->route('POST @doaddentity: /manager/entity/add','ManagerController->doAddEntity');

// Admin routes
$f3->route('GET @admin_managers_list: /admin/managers','Admin\Controller\Manager->listAll');
$f3->route('GET @admin_managers_show: /admin/managers/@managerID','Admin\Controller\Manager->show');



$f3->run();
