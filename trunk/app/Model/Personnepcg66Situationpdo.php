<?php
	class Personnepcg66Situationpdo extends AppModel
	{
		public $name = 'Personnepcg66Situationpdo';

		public $recursive = -1;

		public $actsAs = array(
			'Validation.Autovalidate',
			'ValidateTranslate',
			'Formattable'
		);

		public $belongsTo = array(
			'Personnepcg66' => array(
				'className' => 'Personnepcg66',
				'foreignKey' => 'personnepcg66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Situationpdo' => array(
				'className' => 'Situationpdo',
				'foreignKey' => 'situationpdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $hasMany = array(
			'Traitementpcg66' => array(
				'className' => 'Traitementpcg66',
				'foreignKey' => 'personnepcg66_situationpdo_id',
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
			'Decisionpersonnepcg66' => array(
				'className' => 'Decisionpersonnepcg66',
				'foreignKey' => 'personnepcg66_situationpdo_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);

		/**
		*	Récupération de la liste des situations liées à la personne
		*/

		public function listeMotifsPourDecisions( $personnepcg66_id ) {
			$listeSituations = $this->find(
				'all',
				array(
					'fields' => array(
						'Personnepcg66Situationpdo.id'
					),
					'conditions' => array(
						'Personnepcg66Situationpdo.personnepcg66_id' => $personnepcg66_id
					),
					'contain' => array(
						'Situationpdo' => array(
							'fields' => array(
								'libelle'
							)
						)
					)
				)
			);

			$results = array();
			foreach( $listeSituations as $key => $value ) {
				$results[$value['Personnepcg66Situationpdo']['id']] = $value['Situationpdo']['libelle'];
			}
			return $results;
		}
	}
?>