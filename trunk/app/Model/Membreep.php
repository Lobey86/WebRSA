<?php	
	/**
	 * Code source de la classe Membreep.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Membreep ...
	 *
	 * @package app.Model
	 */
	class Membreep extends AppModel
	{
		public $name = 'Membreep';

		public $actsAs = array(
			'Autovalidate2',
			'ValidateTranslate',
			'Enumerable' => array(
				'fields' => array(
					'qual'
				)
			),
			'Formattable'
		);

		public $validate = array(
			'mail' => array(
				array(
					'rule' => 'email',
					'allowEmpty' => true,
					'message' => 'Le mail n\'est pas valide'
				)
			),
			'tel' => array(
				array(
					'rule' => array( 'between', 10, 14 ),
					'allowEmpty' => true,
					'message' => 'Le numéro de téléphone est composé de 10 chiffres'
				)
			),
		);

		public $belongsTo = array(
			'Fonctionmembreep' => array(
				'className' => 'Fonctionmembreep',
				'foreignKey' => 'fonctionmembreep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasAndBelongsToMany = array(
			'Commissionep' => array(
				'className' => 'Commissionep',
				'joinTable' => 'commissionseps_membreseps',
				'foreignKey' => 'membreep_id',
				'associationForeignKey' => 'commissionep_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'CommissionepMembreep'
			),
			'Ep' => array(
				'className' => 'Ep',
				'joinTable' => 'eps_membreseps',
				'foreignKey' => 'membreep_id',
				'associationForeignKey' => 'ep_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'EpMembreep' // TODO
			),
		);
	}
?>
