<?php
	/**
	 * Code source de la classe DefaultUtilityTest.
	 *
	 * PHP 5.3
	 *
	 * @package Default
	 * @subpackage Test.Case.Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'DefaultUtility', 'Default.Utility' );
	App::uses( 'DefaultAbstractTestCase', 'Default.Test/Case' );

	/**
	 * La classe DefaultUtilityTest ...
	 *
	 * @package Default
	 * @subpackage Test.Case.Utility
	 */

	class DefaultUtilityTest extends DefaultAbstractTestCase
	{
		/**
		 * Données utilisées pour l'évaluation.
		 *
		 * @var array
		 */
		public $data = array(
			'User' => array(
				'id' => 6,
				'username' => 'foo',
				'lastname' => 'bar',
			)
		);

		/**
		 * Test de la méthode DefaultUtility::evaluateString()
		 *
		 * @return void
		 */
		public function testEvaluateString() {
			$result = DefaultUtility::evaluateString( $this->data, '#User.username#' );
			$expected = 'foo';
			$this->assertEqual( $result, $expected, $result );

			$result = DefaultUtility::evaluateString( $this->data, '#User.username# is a #User.lastname#' );
			$expected = 'foo is a bar';
			$this->assertEqual( $result, $expected, $result );
		}

		/**
		 * Test de la méthode DefaultUtility::evaluate()
		 *
		 * @return void
		 */
		public function testEvaluate() {
			$evaluated = array(
				'fuu#User.lastname#baz' => array(
					'#User.id#' => array(
						'#User.username#.#User.lastname#'
					)
				)
			);

			$result = DefaultUtility::evaluate( $this->data, $evaluated );
			$expected = array(
				'fuubarbaz' => array(
					'6' => array(
						'foo.bar'
					)
				)
			);
			$this->assertEqual( $result, $expected, $result );
		}

		/**
		 * Test de la méthode DefaultUtility::linkParams()
		 *
		 * @return void
		 */
		public function testLinkParams() {
			$evaluated = array(
				'fuu#User.lastname#baz' => array(
					'#User.id#' => array(
						'#User.username#.#User.lastname#'
					)
				)
			);

			// TODO: en faire 3 fonctions (avec un cache), dans une classe utilitaire séparée, genre DefaultUrlConverter
			$result = DefaultUtility::linkParams( '/AclUtilities.Users/admin_edit/#User.id##content', array( 'title' => true, 'confirm' => true ), $this->data );
			$expected = array(
				'/AclUtilities.Users/admin_edit',
				array(
					'plugin' => 'acl_utilities',
					'controller' => 'users',
					'action' => 'edit',
					'6',
					'prefix' => 'admin',
					'admin' => true,
					'#' => 'content'
				),
				array(
					'title' => '/AclUtilities.Users/admin_edit/6#content',
					'confirm' => '/AclUtilities.Users/admin_edit/6#content ?',
				),
			);
			$this->assertEqual( $result, $expected, $result );
		}

	}
?>