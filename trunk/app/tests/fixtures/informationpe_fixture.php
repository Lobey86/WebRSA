<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class InformationpeFixture extends CakeAppTestFixture {
		var $name = 'Informationpe';
		var $table = 'informationspe';
		var $import = array( 'table' => 'informationspe', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'nir' => null,
				'nom' => 'Dupond',
				'prenom' => 'Azerty',
				'dtnai' => '1979-01-24',
			),
			array(
				'id' => '2',
				'nir' => null,
				'nom' => 'Dupond',
				'prenom' => 'Qwerty',
				'dtnai' => '1999-07-12',
			),
			array(
				'id' => '3',
				'nir' => null,
				'nom' => 'Dupont',
				'prenom' => 'Qsdfgh',
				'dtnai' => '1966-03-10',
			),
		);
	}
?>
