<?php
use Everyman\Neo4j\Client,
    Everyman\Neo4j\Index\NodeIndex,
    Everyman\Neo4j\Relationship,
    Everyman\Neo4j\Node;

class SearchController {

    function find($f3)
    {
        echo Template::instance($f3)->render('../'.$f3->get('UI').'find.html');
    }


	function doFind($f3) 
	{
		$client = new Client();
		$entities = new NodeIndex($client, 'entity');
			
		if($f3->get('POST.names')&&$f3->get('POST.names')!='')
		{	
			$options=array('limit'=>1000, 'order'=>array('created_at'=>1));
			$names=explode(',',$f3->get('POST.names'));
			
			$r1=MongoSearch::getInstance()->search(array(new Entity()),array('name'=>array('$in'=>[new \MongoRegex('/'.$names[0].'/i'),'$exists'=>true])),$options);
			$r2=MongoSearch::getInstance()->search(array(new Entity()),array('name'=>array('$in'=>[new \MongoRegex('/'.$names[1].'/i'),'$exists'=>true])),$options);
			
			
			$match1 = $entities->findOne('Uid', $r1[0]->getUid());        
			$match2 = $entities->findOne('Uid', $r2[0]->getUid());

			
			$path = $match1->findPathsTo($match2)
				->setmaxDepth(12)
				->getSinglePath();
	//        print_r($path);

			if ($path) {
				$result='[';
				$a=array();
				foreach ($path as $i => $node) {
					$a[]=$node->getProperty('name');

				}
				$f=count($a);
				for ($i=0;$i<$f-1; $i++) {
							$result.= '
			  {source: "'.$a[$i].'", target: "'.$a[$i+1].'", type: "Related"}';
					if($i<$f-2)
					{
						$result.=',';
					}
				}
				
				$result.=']';
				$f3->set('result',$result);
				echo Template::instance($f3)->render('../'.$f3->get('UI').'dofind.html');
				return;
			}
		} else {
		// empty query - we return all
			$matches = $entities->query('Uid:*');
			$result='[';

			foreach($matches as $m)
			{
				$rel = $m->getRelationships();
				foreach($rel as $r)
				{

					$dest=$r->getEndNode()->name;
					$src=$m->getProperty('name');
					$result.= '
	  {source: "'.$src.'", target: "'.$dest.'", type: "Related"}';
						$result.=',';
				}


			}
			rtrim($result, ",");
			$result.=']';
			$f3->set('result',$result);
			echo Template::instance($f3)->render('../'.$f3->get('UI').'dofind.html');
		}
	}

   
} 