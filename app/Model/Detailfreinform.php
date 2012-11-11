<?php	
	/**
	 * Code source de la classe Detailfreinform.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Detailfreinform ...
	 *
	 * @package app.Model
	 */
	class Detailfreinform extends AppModel
	{
		public $name = 'Detailfreinform';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'freinform' => array(
						'type' => 'freinform', 'domain' => 'dsp'
					),
				)
			),
		);

		public $belongsTo = array(
			'Dsp' => array(
				'className' => 'Dsp',
				'foreignKey' => 'dsp_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);
	}
?>