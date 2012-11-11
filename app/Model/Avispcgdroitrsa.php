<?php	
	/**
	 * Code source de la classe Avispcgdroitrsa.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Avispcgdroitrsa ...
	 *
	 * @package app.Model
	 */
	class Avispcgdroitrsa extends AppModel
	{
		public $name = 'Avispcgdroitrsa';

		public $belongsTo = array(
			'Dossier' => array(
				'classname'     => 'Dossier',
				'foreignKey'    => 'dossier_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasMany = array(
			'Condadmin' => array(
				'classname'     => 'Condadmin',
				'foreignKey'    => 'avispcgdroitrsa_id',
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
			'Reducrsa' => array(
				'classname'     => 'Reducrsa',
				'foreignKey'    => 'avispcgdroitrsa_id',
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

		public $validate = array(
			'avisdestpairsa' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'dtavisdestpairsa' => array(
				array(
					'rule' => 'date',
					'message' => 'Veuillez vérifier le format de la date.'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'nomtie' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'typeperstie' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);
	}
?>