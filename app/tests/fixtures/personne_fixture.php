<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class PersonneFixture extends CakeAppTestFixture {
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
				'sexe' => 'M',
				'nati' => null,
				'dtnati' => null,
				'pieecpres' => null,
				'idassedic' => null,
				'numagenpoleemploi' => null,
				'dtinscpoleemploi' => null,
				'numfixe' => null,
				'numport' => null,
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
				'sexe' => 'M',
				'nati' => null,
				'dtnati' => null,
				'pieecpres' => null,
				'idassedic' => null,
				'numagenpoleemploi' => null,
				'dtinscpoleemploi' => null,
				'numfixe' => null,
				'numport' => null,
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
				'sexe' => 'M',
				'nati' => null,
				'dtnati' => null,
				'pieecpres' => null,
				'idassedic' => null,
				'numagenpoleemploi' => null,
				'dtinscpoleemploi' => null,
				'numfixe' => null,
				'numport' => null,
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
				'sexe' => 'F',
				'nati' => null,
				'dtnati' => null,
				'pieecpres' => null,
				'idassedic' => null,
				'numagenpoleemploi' => null,
				'dtinscpoleemploi' => null,
				'numfixe' => null,
				'numport' => null,
			),
			array(
				'id' => '5',
				'foyer_id' => '3',
				'qual' => 'MR',
				'nom' => 'Poiuytr',
				'prenom' => 'Mlkjhg',
				'nomnai' => null,
				'prenom2' => null,
				'prenom3' => null,
				'nomcomnai' => null,
				'dtnai' => '1971-11-10',
				'rgnai' => '1',
				'typedtnai' => null,
				'nir' => null,
				'topvalec' => null,
				'sexe' => null,
				'nati' => null,
				'dtnati' => null,
				'pieecpres' => null,
				'idassedic' => null,
				'numagenpoleemploi' => null,
				'dtinscpoleemploi' => null,
				'numfixe' => null,
				'numport' => null,
			),
			array(
				'id' => '6',
				'foyer_id' => '4',
				'qual' => 'MR',
				'nom' => 'Qsdfghjkl',
				'prenom' => 'Wxcvbn',
				'nomnai' => null,
				'prenom2' => null,
				'prenom3' => null,
				'nomcomnai' => null,
				'dtnai' => '1949-01-02',
				'rgnai' => '1',
				'typedtnai' => null,
				'nir' => null,
				'topvalec' => null,
				'sexe' => null,
				'nati' => null,
				'dtnati' => null,
				'pieecpres' => null,
				'idassedic' => null,
				'numagenpoleemploi' => null,
				'dtinscpoleemploi' => null,
				'numfixe' => null,
				'numport' => null,
			),
			array(
				'id' => '7',
				'foyer_id' => '4',
				'qual' => 'MME',
				'nom' => 'Qsdfghjkl',
				'prenom' => 'Poiut',
				'nomnai' => null,
				'prenom2' => null,
				'prenom3' => null,
				'nomcomnai' => null,
				'dtnai' => '1980-05-07',
				'rgnai' => '1',
				'typedtnai' => null,
				'nir' => null,
				'topvalec' => null,
				'sexe' => null,
				'nati' => null,
				'dtnati' => null,
				'pieecpres' => null,
				'idassedic' => null,
				'numagenpoleemploi' => null,
				'dtinscpoleemploi' => null,
				'numfixe' => null,
				'numport' => null,
			),
			array(
				'id' => '8',
				'foyer_id' => '4',
				'qual' => 'MLL',
				'nom' => 'Qsdfghjkl',
				'prenom' => 'Azertyui',
				'nomnai' => null,
				'prenom2' => null,
				'prenom3' => null,
				'nomcomnai' => null,
				'dtnai' => '2001-02-07',
				'rgnai' => '1',
				'typedtnai' => null,
				'nir' => null,
				'topvalec' => null,
				'sexe' => null,
				'nati' => null,
				'dtnati' => null,
				'pieecpres' => null,
				'idassedic' => null,
				'numagenpoleemploi' => null,
				'dtinscpoleemploi' => null,
				'numfixe' => null,
				'numport' => null,
			),
			array(
				'id' => '9',
				'foyer_id' => '5',
				'qual' => 'MR',
				'nom' => 'Tyuio',
				'prenom' => 'Hrtyu',
				'nomnai' => null,
				'prenom2' => null,
				'prenom3' => null,
				'nomcomnai' => null,
				'dtnai' => '1995-02-07',
				'rgnai' => '1',
				'typedtnai' => null,
				'nir' => null,
				'topvalec' => null,
				'sexe' => null,
				'nati' => null,
				'dtnati' => null,
				'pieecpres' => null,
				'idassedic' => null,
				'numagenpoleemploi' => null,
				'dtinscpoleemploi' => null,
				'numfixe' => null,
				'numport' => null,
			),
			array(
				'id' => '10',
				'foyer_id' => '5',
				'qual' => 'MME',
				'nom' => 'Tyuio',
				'prenom' => 'Xrtyui',
				'nomnai' => null,
				'prenom2' => null,
				'prenom3' => null,
				'nomcomnai' => null,
				'dtnai' => '1996-02-07',
				'rgnai' => '1',
				'typedtnai' => null,
				'nir' => null,
				'topvalec' => null,
				'sexe' => null,
				'nati' => null,
				'dtnati' => null,
				'pieecpres' => null,
				'idassedic' => null,
				'numagenpoleemploi' => null,
				'dtinscpoleemploi' => null,
				'numfixe' => null,
				'numport' => null,
			),
			array(
				'id' => '11',
				'foyer_id' => '5',
				'qual' => 'MR',
				'nom' => 'Tyuio',
				'prenom' => 'Zfghjk',
				'nomnai' => null,
				'prenom2' => null,
				'prenom3' => null,
				'nomcomnai' => null,
				'dtnai' => '2009-02-07',
				'rgnai' => '1',
				'typedtnai' => null,
				'nir' => null,
				'topvalec' => null,
				'sexe' => null,
				'nati' => null,
				'dtnati' => null,
				'pieecpres' => null,
				'idassedic' => null,
				'numagenpoleemploi' => null,
				'dtinscpoleemploi' => null,
				'numfixe' => null,
				'numport' => null,
			),
			array(
				'id' => '667',
				'foyer_id' => '666',
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
				'numagenpoleemploi' => null,
				'dtinscpoleemploi' => null,
				'numfixe' => null,
				'numport' => null,
			),
			array(
				'id' => '1001',
				'foyer_id' => '1001',
				'qual' => 'M',
				'nom' => 'mille',
				'prenom' => 'un',
				'nomnai' => null,
				'prenom2' => null,
				'prenom3' => null,
				'nomcomnai' => null,
				'dtnai' => '1969-01-01',
				'rgnai' => '1',
				'typedtnai' => 'N',
				'nir' => '169019313371001',
				'topvalec' => null,
				'sexe' => '1',
				'nati' => null,
				'dtnati' => null,
				'pieecpres' => null,
				'idassedic' => null,
				'numagenpoleemploi' => null,
				'dtinscpoleemploi' => null,
				'numfixe' => null,
				'numport' => null,
			),
			array(
				'id' => '2002',
				'foyer_id' => '2002',
				'qual' => 'M',
				'nom' => 'deuxmille',
				'prenom' => 'deux',
				'nomnai' => null,
				'prenom2' => null,
				'prenom3' => null,
				'nomcomnai' => null,
				'dtnai' => '1969-01-01',
				'rgnai' => '1',
				'typedtnai' => 'N',
				'nir' => '169019313372002',
				'topvalec' => null,
				'sexe' => '1',
				'nati' => null,
				'dtnati' => null,
				'pieecpres' => null,
				'idassedic' => null,
				'numagenpoleemploi' => null,
				'dtinscpoleemploi' => null,
				'numfixe' => null,
				'numport' => null,
			),
			array(
				'id' => '3003',
				'foyer_id' => '3003',
				'qual' => 'M',
				'nom' => 'troismille',
				'prenom' => 'troisenfant',
				'nomnai' => null,
				'prenom2' => null,
				'prenom3' => null,
				'nomcomnai' => null,
				'dtnai' => '1989-01-01',
				'rgnai' => '1',
				'typedtnai' => 'N',
				'nir' => '169019313373003',
				'topvalec' => null,
				'sexe' => '1',
				'nati' => null,
				'dtnati' => null,
				'pieecpres' => null,
				'idassedic' => null,
				'numagenpoleemploi' => null,
				'dtinscpoleemploi' => null,
				'numfixe' => null,
				'numport' => null,
			),
			array(
				'id' => '4004',
				'foyer_id' => '4004',
				'qual' => 'M',
				'nom' => 'quatremille',
				'prenom' => 'quatre',
				'nomnai' => null,
				'prenom2' => null,
				'prenom3' => null,
				'nomcomnai' => null,
				'dtnai' => '1969-01-01',
				'rgnai' => '1',
				'typedtnai' => 'N',
				'nir' => '169019313374004',
				'topvalec' => null,
				'sexe' => '1',
				'nati' => null,
				'dtnati' => null,
				'pieecpres' => null,
				'idassedic' => null,
				'numagenpoleemploi' => null,
				'dtinscpoleemploi' => null,
				'numfixe' => null,
				'numport' => null,
			),
		);
	}

?>
