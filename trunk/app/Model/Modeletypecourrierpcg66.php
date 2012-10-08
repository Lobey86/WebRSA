<?php
	class Modeletypecourrierpcg66 extends AppModel
	{
		public $name = 'Modeletypecourrierpcg66';

		public $order = 'Modeletypecourrierpcg66.name ASC';

		public $validate = array(
			'name' => array(
				array(
					'rule' => 'isUnique',
					'message' => 'Cette valeur est déjà utilisée'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);

		public $belongsTo = array(
			'Typecourrierpcg66' => array(
				'className' => 'Typecourrierpcg66',
				'foreignKey' => 'typecourrierpcg66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasMany = array(
			'Piecemodeletypecourrierpcg66' => array(
				'className' => 'Piecemodeletypecourrierpcg66',
				'foreignKey' => 'modeletypecourrierpcg66_id',
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
			'Modeletraitementpcg66' => array(
				'className' => 'Modeletraitementpcg66',
				'foreignKey' => 'modeletypecourrierpcg66_id',
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

// 		public $hasAndBelongsToMany = array(
// 			// Test liaison avec situationspdos
// 			'Situationpdo' => array(
// 				'className' => 'Situationpdo',
// 				'joinTable' => 'modelestypescourrierspcgs66_situationspdos',
// 				'foreignKey' => 'modeletypecourrierpcg66_id',
// 				'associationForeignKey' => 'situationpdo_id',
// 				'unique' => true,
// 				'conditions' => '',
// 				'fields' => '',
// 				'order' => '',
// 				'limit' => '',
// 				'offset' => '',
// 				'finderQuery' => '',
// 				'deleteQuery' => '',
// 				'insertQuery' => '',
// 				'with' => 'Modeletypecourrierpcg66Situationpdo'
// 			)
// 		);

		/**
		 * Retourne la liste des modèles odt paramétrés pour le impressions de
		 * cette classe.
		 *
		 * @return array
		 */
		public function modelesOdt() {
			$prefix = 'PCG66'.DS.'Traitementpcg66'.DS;

			$items = $this->find(
				'all',
				array(
					'fields' => array(
						'( \''.$prefix.'\' || "'.$this->alias.'"."modeleodt" || \'.odt\' ) AS "'.$this->alias.'__modele"',
					),
					'conditions' => array( ''.$this->alias.'.modeleodt IS NOT NULL' ),
					'recursive' => -1
				)
			);
			return Set::extract( $items, '/'.$this->alias.'/modele' );
		}
	}
?>
