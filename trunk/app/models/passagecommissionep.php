<?php
	class Passagecommissionep extends AppModel
	{
		/**
		*
		*/

		public $recursive = -1;

		/**
		*
		*/

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable',
			'Enumerable' => array(
				'fields' => array(
					'etatdossierep'
				)
			)
		);

		/**
		*
		*/

		public $belongsTo = array(
			'Commissionep' => array(
				'className' => 'Commissionep',
				'foreignKey' => 'commissionep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Dossierep' => array(
				'className' => 'Dossierep',
				'foreignKey' => 'dossierep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);



		public $hasMany = array(
			'Decisionreorientationep93' => array(
				'className' => 'Decisionreorientationep93',
				'foreignKey' => 'passagecommissionep_id',
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
			'Decisionnonorientationproep58' => array(
				'className' => 'Decisionnonorientationproep58',
				'foreignKey' => 'passagecommissionep_id',
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
			'Decisionregressionorientationep58' => array(
				'className' => 'Decisionregressionorientationep58',
				'foreignKey' => 'passagecommissionep_id',
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
			'Decisionsanctionep58' => array(
				'className' => 'Decisionsanctionep58',
				'foreignKey' => 'passagecommissionep_id',
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
			'Decisionnonrespectsanctionep93' => array(
				'className' => 'Decisionnonrespectsanctionep93',
				'foreignKey' => 'passagecommissionep_id',
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
			'Decisionnonorientationproep93' => array(
				'className' => 'Decisionnonorientationproep93',
				'foreignKey' => 'passagecommissionep_id',
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
			'Decisiondefautinsertionep66' => array(
				'className' => 'Decisiondefautinsertionep66',
				'foreignKey' => 'passagecommissionep_id',
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
			'Decisionsaisinebilanparcoursep66' => array(
				'className' => 'Decisionsaisinebilanparcoursep66',
				'foreignKey' => 'passagecommissionep_id',
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
			'Decisionsaisinepdoep66' => array(
				'className' => 'Decisionsaisinepdoep66',
				'foreignKey' => 'passagecommissionep_id',
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