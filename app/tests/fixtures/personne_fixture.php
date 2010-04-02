<?php

	class PersonneFixture extends CakeTestFixture {
		var $name = 'Personne';
		var $table = 'personnes';
		var $import = array( 'table' => 'personnes', 'connection' => 'default', 'records' => false);
		var $records = array(
								array(
										'id' => '1',
										'foyer_id' => '1',
										'qual' => 'MR',
										'nom' => 'Dupond',
										'prenom' => 'Azerty',
										'nomnai' => null,
										'prenom2' => null,
										'prenom3' => null,
										'nomcomnai' => null,
										'dtnai' => '1979-01-24',
										'rgnai' => '1',
										'typedtnai' => null,
										'nir' => null,
										'topvalec' => null,
										'sexe' => null,
										'nati' => null,
										'dtnati' => null,
										'pieecpres' => null,
										'idassedic' => null,
								),
								array(
										'id' => '2',
										'foyer_id' => '1',
										'qual' => 'MR',
										'nom' => 'Dupond',
										'prenom' => 'Qwerty',
										'nomnai' => null,
										'prenom2' => null,
										'prenom3' => null,
										'nomcomnai' => null,
										'dtnai' => '1999-07-12',
										'rgnai' => '1',
										'typedtnai' => null,
										'nir' => null,
										'topvalec' => null,
										'sexe' => null,
										'nati' => null,
										'dtnati' => null,
										'pieecpres' => null,
										'idassedic' => null,
								),
								array(
										'id' => '3',
										'foyer_id' => '2',
										'qual' => 'MR',
										'nom' => 'Dupont',
										'prenom' => 'Qsdfgh',
										'nomnai' => null,
										'prenom2' => null,
										'prenom3' => null,
										'nomcomnai' => null,
										'dtnai' => '1966-03-10',
										'rgnai' => '2',
										'typedtnai' => null,
										'nir' => null,
										'topvalec' => null,
										'sexe' => null,
										'nati' => null,
										'dtnati' => null,
										'pieecpres' => null,
										'idassedic' => null,
								),
								array(
										'id' => '4',
										'foyer_id' => '2',
										'qual' => 'MME',
										'nom' => 'Dupont',
										'prenom' => 'Zsdfgh',
										'nomnai' => null,
										'prenom2' => null,
										'prenom3' => null,
										'nomcomnai' => null,
										'dtnai' => '1968-11-02',
										'rgnai' => '1',
										'typedtnai' => null,
										'nir' => null,
										'topvalec' => null,
										'sexe' => null,
										'nati' => null,
										'dtnati' => null,
										'pieecpres' => null,
										'idassedic' => null,
								),
		);
	}

?>