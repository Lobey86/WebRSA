<?php
	/**
	 * Code source de la classe ConfigurableQueryFieldsTest.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'ConfigurableQueryFields', 'Utility' );

	/**
	 * La classe ConfigurableQueryFieldsTest ...
	 *
	 * @package app.Test.Case.Utility
	 */
	class ConfigurableQueryFieldsTest extends CakeTestCase
	{
		/**
		 * Test de la méthode ConfigurableQueryFields::getErrors() sans erreur.
		 *
		 * @return void
		 */
		public function testGetErrorsNoError() {
			Configure::write( 'Foos.index', array( 'Foo.bar', 'Foo.baz' ) );
			$query = array( 'fields' => Hash::normalize( array( 'Foo.bar', 'Foo.baz' ) ) );

			$result = ConfigurableQueryFields::getErrors( array( 'Foos.index' ), $query );
			$expected = array(
				'Foos.index' => array(
					'success' => true,
					'message' => null,
					'value' => "array (\n  0 => 'Foo.bar',\n  1 => 'Foo.baz',\n)"
				)
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode ConfigurableQueryFields::getErrors() avec tous les
		 * champs demandés en erreur.
		 *
		 * @return void
		 */
		public function testGetErrorsAllErrors() {
			Configure::write( 'Foos.index', array( 'Foo.bar', 'Foo.baz' ) );
			$query = array( 'fields' => array() );

			$result = ConfigurableQueryFields::getErrors( array( 'Foos.index' ), $query );
			$expected = array(
				'Foos.index' => array(
					'success' => false,
					'message' => 'Les champs suivants sont demandés dans la configuration de Foos.index mais pas disponibles: Foo.bar, Foo.baz',
					'value' => "array (\n  0 => 'Foo.bar',\n  1 => 'Foo.baz',\n)"
				)
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode ConfigurableQueryFields::getFieldsByKeys().
		 *
		 * @return void
		 */
		public function testGetFieldsByKeys() {
			Configure::write( 'Foos.index.fields', array( 'Foo.bar', 'Foo.baz' ) );
			Configure::write( 'Foos.index.innerTable', array( 'Foo.bar', 'Bar.foo' ) );
			Configure::write( 'Foos.exportcsv', array( 'Bar.baz', 'Baz.bar' ) );

			$fields = array( 'Foo.bar', 'Foo.baz', 'Bar.foo', 'Bar.baz', 'Baz.bar' );
			$query = array( 'fields' => array_combine( $fields, $fields ) );

			$result = ConfigurableQueryFields::getFieldsByKeys( array( 'Foos.index.fields', 'Foos.index.innerTable' ), $query );
			$expected = array(
				'fields' => array(
					'Foo.bar' => 'Foo.bar',
					'Foo.baz' => 'Foo.baz',
					'Bar.foo' => 'Bar.foo'
				)
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode ConfigurableQueryFields::exportQueryFields().
		 *
		 * @return void
		 */
		public function testExportQueryFields() {
			$fields = array( 'Foo.bar', 'Foo.baz', 'Bar.foo', 'Bar.baz', 'Baz.bar' );
			$query = array( 'fields' => array_combine( $fields, $fields ) );

			$fileName = TMP.DS.'logs'.DS.__CLASS__.'__'.__FUNCTION__.'.csv';
			ConfigurableQueryFields::exportQueryFields( $query, 'foos', $fileName );

			$result = file_get_contents( $fileName );
			unlink( $fileName );
			$expected = "\"Champ\",\"Intitulé\"\nFoo.bar,Foo.bar\nFoo.baz,Foo.baz\nBar.foo,Bar.foo\nBar.baz,Bar.baz\nBaz.bar,Baz.bar";
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>