<?php
	/**
	 * Code source de la classe Motifnonsouhaitfp93.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Motifnonsouhaitfp93 ...
	 *
	 * @package app.Model
	 */
	class Motifnonsouhaitfp93 extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Motifnonsouhaitfp93';

		/**
		 * Récursivité par défaut du modèle.
		 *
		 * @var integer
		 */
		public $recursive = -1;

		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Postgres.PostgresAutovalidate',
			'Validation2.Validation2Formattable',
		);

		/**
		 * Associations "Has many".
		 *
		 * @var array
		 */
		public $hasMany = array(
			'Ficheprescription93' => array(
				'className' => 'Ficheprescription93',
				'foreignKey' => 'motifnonsouhaitfp93_id',
				'conditions' => null,
				'order' => null,
				'limit' => null,
				'offset' => null,
				'dependent' => true,
				'exclusive' => null,
				'finderQuery' => null
			),
		);
	}
?>