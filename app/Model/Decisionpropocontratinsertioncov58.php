<?php	
	/**
	 * Code source de la classe Decisionpropocontratinsertioncov58.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Decisionpropocontratinsertioncov58 ...
	 *
	 * @package app.Model
	 */
	class Decisionpropocontratinsertioncov58 extends AppModel
	{
		public $name = 'Decisionpropocontratinsertioncov58';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate2',
			'ValidateTranslate',
			'Formattable' => array(
				'suffix' => array(
					'structurereferente_id',
					'referent_id'
				)
			),
			'Enumerable' => array(
				'fields' => array(
					'etapecov',
					'decisioncov'
				)
			)
		);

		public $belongsTo = array(
			'Passagecov58' => array(
				'className' => 'Passagecov58',
				'foreignKey' => 'passagecov58_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		// TODO: lorsqu'on pourra reporter les dossiers,
		// il faudra soit faire soit un report, soit les validations ci-dessous
		// FIXME: dans ce cas, il faudra permettre au champ decision de prendre la valeur NULL

		/**
		* Les règles de validation qui seront utilisées lors de la validation
		* en COV des décisions de la thématique
		*/

		public $validateFinalisation = array(
			'decisioncov' => array(
				array(
					'rule' => array( 'notEmpty' )
				)
			)
		);

	}
?>