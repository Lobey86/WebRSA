<?php
	class Participantcomite extends AppModel
	{
		public $name = 'Participantcomite';

		public $order = 'Participantcomite.id ASC';

		public $actsAs = array(
			'Enumerable'
		);

		public $hasAndBelongsToMany = array(
			'Comiteapre' => array(
				'className' => 'Comiteapre',
				'joinTable' => 'comitesapres_participantscomites',
				'foreignKey' => 'participantcomite_id',
				'associationForeignKey' => 'comiteapre_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'ComiteapreParticipantcomite'
			)
		);

		public $validate = array(
			'nom' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'qual' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'prenom' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'organisme' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'fonction' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
//             'numtel' => array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//             ),
//             'mail' => array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//             )
		);
	}
?>
