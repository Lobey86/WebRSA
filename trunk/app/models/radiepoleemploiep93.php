<?php
	App::import( 'Model', 'Radiepoleemploiep' );
	
	class Radiepoleemploiep93 extends Radiepoleemploiep
	{
		public $name = 'Radiepoleemploiep93';
		
		public $decisionName = 'Decisonradiepoleemploiep93';

		public $hasMany = array(
			'Decisionradiepoleemploiep93' => array(
				'className' => 'Decisionradiepoleemploiep93',
				'foreignKey' => 'radiepoleemploiep93_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
		);
		
	}
?>