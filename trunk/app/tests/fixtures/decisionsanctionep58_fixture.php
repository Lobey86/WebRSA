<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class Decisionsanctionep58Fixture extends CakeAppTestFixture {
		var $name = 'Decisionsanctionep58';
		var $table = 'decisionssanctionseps58';
		var $import = array( 'table' => 'decisionssanctionseps58', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'sanctionep58_id' => '1',
				'etape' => 'cg',
				'decision' => 'sanction',
				'commentaire' => null,
				'created' => null,
				'modified' => null,
			),
		);
	}

?>
