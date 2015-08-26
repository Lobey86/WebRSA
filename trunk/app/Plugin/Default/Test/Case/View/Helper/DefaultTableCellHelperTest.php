<?php
	/**
	 * Code source de la classe DefaultTableCellHelperTest.
	 *
	 * PHP 5.3
	 *
	 * @package Default
	 * @subpackage Test.Case.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'View', 'View' );
	App::uses( 'AppHelper', 'View/Helper' );
	App::uses( 'DefaultTableCellHelper', 'Default.View/Helper' );
	App::uses( 'DefaultAbstractTestCase', 'Default.Test/Case' );
	App::uses( 'CakeTestSession', 'CakeTest.Model/Datasource' );

	/**
	 * La classe DefaultTableCellHelperTest ...
	 *
	 * @package Default
	 * @subpackage Test.Case.View.Helper
	 */
	class DefaultTableCellHelperTest extends DefaultAbstractTestCase
	{
		/**
		 * Fixtures utilisés par ces tests unitaires.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'core.Apple'
		);

		/**
		 * Préparation du test.
		 *
		 * @return void
		 */
		public function setUp() {
			parent::setUp();
			CakeTestSession::start();

			$controller = null;
			$this->View = new View( $controller );
			$this->DefaultTableCell = new DefaultTableCellHelper( $this->View );

			$data = array(
				'Apple' => array(
					'id' => 6,
					'color' => 'red',
					'code' => "-0402\n\r-0404\n\r-0405",
					'name' => ''
				),
				'Foo' => array( 'bar' => array() )
			);
			$this->DefaultTableCell->set( $data );
		}

		/**
		 * Nettoyage postérieur au test.
		 *
		 * @return void
		 */
		public function tearDown() {
			CakeTestSession::destroy();
			parent::tearDown();
			unset( $this->View, $this->DefaultTableCell );
		}

		/**
		 * test case startup
		 *
		 * @return void
		 */
		public static function setupBeforeClass() {
			CakeTestSession::setupBeforeClass();
		}

		/**
		 * cleanup after test case.
		 *
		 * @return void
		 */
		public static function teardownAfterClass() {
			CakeTestSession::teardownAfterClass();
		}

		/**
		 * Test de la méthode DefaultTableCellHelper::data()
		 *
		 * @medium
		 */
		public function testData() {
			// Test simple
			$htmlAttributes = array();
			$result = $this->DefaultTableCell->data( 'Apple.id', $htmlAttributes );
			$expected = array(
				'6',
				array( 'class' => 'data integer positive', ),
			);
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			// Test avec des options, dont la valeur est traduite
			$htmlAttributes = array( 'options' => array( 'red' => 'Foo' ) );
			$result = $this->DefaultTableCell->data( 'Apple.color', $htmlAttributes );
			$expected = array(
				'Foo',
				array( 'class' => 'data string ', ),
			);
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			// Test avec des options, mais dont la valeur n'est pas traduite
			$htmlAttributes = array( 'options' => array( 'blue' => 'Foo' ) );
			$result = $this->DefaultTableCell->data( 'Apple.color', $htmlAttributes );
			$expected = array(
				'red',
				array( 'class' => 'data string ', ),
			);
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			// Test avec un label spécifié, ce qui ne doit rien changer
			$result = $this->DefaultTableCell->data( 'Apple.id', array( 'label' => 'Test label' ) );
			$expected = array(
				'6',
				array( 'class' => 'data integer positive', ),
			);
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			// Test avec une liste
			$options = array(
				'0401' => 'Aucune difficulté',
				'0402' => 'Santé',
				'0403' => 'Reconnaissance de la qualité de travailleur handicapé',
				'0404' => 'Lecture, écriture ou compréhension du français',
				'0405' => 'Démarches et formalités administratives',
				'0406' => 'Endettement',
				'0407' => 'Autres'
			);
			$result = $this->DefaultTableCell->data( 'Apple.code', array( 'type' => 'list', 'options' => $options ) );
			$expected = array(
				'<ul><li>Santé</li><li>Lecture, écriture ou compréhension du français</li><li>Démarches et formalités administratives</li></ul>',
				array(
					'class' => 'data list text',
				)
			);
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			// Test avec une liste vide
			$data = array(
				'Apple' => array(
					'id' => 6,
					'color' => 'red',
					'code' => ''
				)
			);
			$this->DefaultTableCell->set( $data );
			$result = $this->DefaultTableCell->data( 'Apple.code', array( 'type' => 'list', 'options' => $options ) );
			$expected = array(
				'',
				array(
					'class' => 'data list text',
				)
			);
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			// Test avec un datetime
			$data = array(
				'Apple' => array(
					'id' => 6,
					'created' => '2015-07-03 11:58:13'
				)
			);
			$this->DefaultTableCell->set( $data );
			$result = $this->DefaultTableCell->data( 'Apple.created' );
			$expected = array(
				'03/07/2015 à 11:58:13',
				array(
					'class' => 'data datetime ',
				)
			);
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			// Test avec un datetime pour lequel on force le format
			$data = array(
				'Apple' => array(
					'id' => 6,
					'created' => '2015-07-03 11:58:13'
				)
			);
			$this->DefaultTableCell->set( $data );
			$result = $this->DefaultTableCell->data( 'Apple.created', array( 'format' => '%A %e %B %Y %H:%M' ) );
			$expected = array(
				'vendredi  3 juillet 2015 11:58',
				array(
					'class' => 'data datetime ',
				)
			);
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DefaultTableCellHelper::action()
		 *
		 * @return void
		 */
		public function testAction() {
			$_SESSION['Auth']['Permissions']['Module:Apples'] = true;

			$htmlAttributes = array();
			$result = $this->DefaultTableCell->action( '/Apples/view/#Apple.id#', $htmlAttributes );
			$expected = array(
				'<a href="/apples/view/6" title="/Apples/view/6" class="apples view">/Apples/view</a>',
				array(
					'class' => 'action',
					'for' => NULL,
				),
			);
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$htmlAttributes = array( 'for' => 'ApplesView' );
			$result = $this->DefaultTableCell->action( '/Apples/view/#Apple.id#', $htmlAttributes );
			$expected = array(
				'<a href="/apples/view/6" title="/Apples/view/6" class="apples view">/Apples/view</a>',
				array(
					'class' => 'action',
					'for' => 'ApplesView',
				),
			);
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DefaultTableCellHelper::action() avec message de
		 * confirmation.
		 *
		 * @return void
		 */
		public function testActionConfirm() {
			$_SESSION['Auth']['Permissions']['Module:Apples'] = true;

			$htmlAttributes = array( 'confirm' => true );
			$result = $this->DefaultTableCell->action( '/Apples/view/#Apple.id#', $htmlAttributes );
			$expected = array(
				'<a href="/apples/view/6" title="/Apples/view/6" class="apples view" onclick="return confirm(&#039;/Apples/view/6 ?&#039;);">/Apples/view</a>',
				array(
					'class' => 'action',
					'for' => NULL,
				),
			);
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$htmlAttributes = array( 'for' => 'ApplesView' );
			$result = $this->DefaultTableCell->action( '/Apples/view/#Apple.id#', $htmlAttributes );
			$expected = array(
				'<a href="/apples/view/6" title="/Apples/view/6" class="apples view">/Apples/view</a>',
				array(
					'class' => 'action',
					'for' => 'ApplesView',
				),
			);
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DefaultTableCellHelper::input()
		 *
		 * @return void
		 */
		public function testInput() {
			$htmlAttributes = array();
			$result = $this->DefaultTableCell->input( 'data[Apple][id]', $htmlAttributes );
			$expected = array(
				'<input type="hidden" name="data[Apple][id]" id="AppleId"/>',
				array(
					'class' => 'input integer',
				),
			);
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DefaultTableCellHelper::auto()
		 *
		 * @return void
		 */
		public function testAuto() {
			$htmlAttributes = array();

			$result = $this->DefaultTableCell->auto( 'Apple.id', $htmlAttributes );
			$expected = array(
				'6',
				array( 'class' => 'data integer positive', ),
			);
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$result = $this->DefaultTableCell->auto( '/Apples/view/#Apple.id#', $htmlAttributes );
			$expected = array(
				'<a href="/apples/view/6" title="/Apples/view/6" class="apples view">/Apples/view</a>',
				array(
					'class' => 'action',
					'for' => NULL,
				),
			);

			$htmlAttributes = array();
			$result = $this->DefaultTableCell->auto( 'data[Apple][id]', $htmlAttributes );
			$expected = array(
				'<input type="hidden" name="data[Apple][id]" id="AppleId"/>',
				array(
					'class' => 'input integer',
				),
			);
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$result = $this->DefaultTableCell->auto( 'data[Apple][id]', array( 'type'=> 'text', 'disabled' => '( "#Apple.id#" == "6" )' ) );
			$expected = array(
				'<div class="input text"><label for="AppleId">Id</label><input name="data[Apple][id]" disabled="disabled" type="text" id="AppleId"/></div>',
				array( 'class' => 'input text', ),
			);
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}
	}
?>