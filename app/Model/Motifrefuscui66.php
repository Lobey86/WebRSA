<?php
	/**
	 * Code source de la classe Motifrefuscui66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Motifrefuscui66 ...
	 *
	 * @package app.Model
	 */
	class Motifrefuscui66 extends AppModel
	{
		public $name = 'Motifrefuscui66';

		public $actsAs = array(
			'Postgres.PostgresAutovalidate',
			'Formattable'
		);

		public $validate = array(
			'name' => array(
				array(
					'rule' => 'isUnique',
					'message' => 'Valeur déjà utilisée'
				)
			)
		);
	}
?>