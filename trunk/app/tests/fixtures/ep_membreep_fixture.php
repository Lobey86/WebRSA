<?php

	class EpMembreepFixture extends CakeTestFixture {
		var $name = 'EpMembreep';
		var $table = 'eps_membreseps';
		var $import = array( 'table' => 'eps_membreseps', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'ep_id' => '2',
				'membreep_id' => '1',
			),
			array(
				'id' => '2',
				'ep_id' => '3',
				'membreep_id' => '2',
			),
		);
	}

?>
