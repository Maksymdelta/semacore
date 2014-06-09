<?php

$f3=require('lib/base.php');

$f3->set('DEBUG',3);
if ((float)PCRE_VERSION<7.9)
 trigger_error('PCRE version is out of date');
$f3->set('AUTOLOAD','classes/;controllers/;lib');
$f3->set('CACHE', false); // was true
require('lib/vendor/autoload.php');
$f3->config('config.ini');

\Template::instance()->extend('handlebars','Admin\Template\Helpers::handlebars');
$f3->set('mongo', new \DB\Mongo($f3->get('mongoserver'), $f3->get('mongodb')));

$f3->route('GET @list: /manager/list','ManagerController->listObjects');
$f3->route('POST @addinfobit: /manager/entity/addinfobit','ManagerController->addInfobitToEntity');
$f3->route('POST @addinfobittorelationship: /manager/relationship/addinfobit','ManagerController->addInfobitToRelationship');
$f3->route('GET @deleteinfobit: /manager/entity/@entityid/deleteinfobit/@infobitid','ManagerController->deleteInfobitFromEntity');
$f3->route('GET @deleterelationinfobit: /manager/relationship/@relid/deleteinfobit/@infobitid','ManagerController->deleteInfobitFromRelationship');
$f3->route('POST @listsearch: /manager/search','ManagerController->listSearch');
$f3->route('GET @entityadd: /manager/entity/add','ManagerController->addEntity');
$f3->route('GET @relationshipadd: /manager/relationship/add/@entityid','ManagerController->addRelationshipToEntity');
$f3->route('POST @doaddrelationship: /manager/relationship/add','ManagerController->doAddRelationshipToEntity');
$f3->route('GET @newrelationship: /manager/relationship/new','ManagerController->addRelationship');
$f3->route('POST @doaddnewrelationship: /manager/relationship/new/add','ManagerController->doAddRelationship');
$f3->route('POST @changerelationship: /manager/relationship/change [ajax]','ManagerController->changeRelationship');
$f3->route('POST @replaceentity: /manager/relationship/replaceentity [ajax]','ManagerController->replaceEntity');
$f3->route('POST @relationshipsettype: /manager/relationship/settype [ajax]','ManagerController->relationshipSetType');
$f3->route('GET @entityedit: /manager/editobject/entity/@id','ManagerController->editEntity');
$f3->route('GET @entityquery: /manager/entity/@entityid/query/@query.json [ajax]','ManagerController->queryEntity');
$f3->route('GET /manager/entity/query/@query.json [ajax]','ManagerController->queryEntity');
$f3->route('POST @doeditentity: /manager/entity/edit','ManagerController->doEditEntity');
$f3->route('GET @relationshipedit: /manager/editobject/relationship/@id','ManagerController->editRelationship');
$f3->route('POST @doeditrelationship: /manager/relationship/edit','ManagerController->doEditRelationship');
$f3->route('GET @entitydelete: /manager/deleteobject/entity/@id','ManagerController->deleteEntity');
$f3->route('GET @relationshipdelete: /manager/deleteobject/relationship/@id','ManagerController->deleteRelationship');
$f3->route('GET /manager/entity/@entityid/deleterelation/@id','ManagerController->deleteRelationship');
$f3->route('POST @doaddentity: /manager/entity/add','ManagerController->doAddEntity');

// Admin routes
$f3->route('GET @manageradd: /admin/managers/add','AdminManager->addManager');
$f3->route('POST @doaddmanager: /admin/managers/add','AdminManager->doAddManager');
$f3->route('GET @manageredit: /admin/manager/@id','AdminManager->editManager');
$f3->route('POST @domanageredit: /admin/managers/edit','AdminManager->doEditManager');


$f3->route('GET @admin_managers_list: /admin/managers','AdminManager->listAll');
$f3->route('GET @admin_managers_show: /admin/managers/@managerID','AdminManager->show');

$f3->route('GET @find: /admin/find','SearchController->find');
$f3->route('POST @dofind: /admin/find','SearchController->doFind');

$f3->run();
