<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class StructurereferenteFixture extends CakeAppTestFixture {
		var $name = 'Structurereferente';
		var $table = 'structuresreferentes';
		var $import = array( 'table' => 'structuresreferentes', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'typeorient_id' => '1',
				'lib_struc' => 'Pole emploi Mont Sud',
				'num_voie' => '125',
				'type_voie' => 'BEGI',
				'nom_voie' => 'Alco',
				'code_postal' => '34090',
				'ville' => 'Montpellier',
				'code_insee' => '34095',
				'filtre_zone_geo' => null,
				'contratengagement' => null,
				'apre' => 'O',
				'orientation' => 'O',
				'pdo' => 'O',
			),
			array(
				'id' => '2',
				'typeorient_id' => '1',
				'lib_struc' => 'Assedic Nimes',
				'num_voie' => '44',
				'type_voie' => 'ARC',
				'nom_voie' => 'Parrot',
				'code_postal' => '30000',
				'ville' => 'Nimes',
				'code_insee' => '30009',
				'filtre_zone_geo' => null,
				'contratengagement' => 'O',
				'apre' => null,
				'orientation' => 'O',
				'pdo' => 'O',
			),
			array(
				'id' => '3',
				'typeorient_id' => '2',
				'lib_struc' => 'MSA du Gard',
				'num_voie' => '48',
				'type_voie' => 'BAST',
				'nom_voie' => 'Paul Condorcet',
				'code_postal' => '30900',
				'ville' => 'Nimes',
				'code_insee' => '30000',
				'filtre_zone_geo' => null,
				'contratengagement' => 'O',
				'apre' => null,
				'orientation' => 'O',
				'pdo' => 'O',
			),
			array(
				'id' => '4',
				'typeorient_id' => '3',
				'lib_struc' => 'Conseil Général de l\'Hérault',
				'num_voie' => '10',
				'type_voie' => 'AUT',
				'nom_voie' => 'Georges Freche',
				'code_postal' => '34000',
				'ville' => 'Montpellier',
				'code_insee' => '34005',
				'filtre_zone_geo' => null,
				'contratengagement' => 'O',
				'apre' => 'O',
				'orientation' => 'O',
				'pdo' => 'O',
			),
			array(
				'id' => '5',
				'typeorient_id' => '3',
				'lib_struc' => 'Organisme ACAL Vauvert',
				'num_voie' => '48',
				'type_voie' => 'BER',
				'nom_voie' => 'Georges Freche',
				'code_postal' => '30600',
				'ville' => 'Vauvert',
				'code_insee' => '30610',
				'filtre_zone_geo' => null,
				'contratengagement' => 'O',
				'apre' => 'O',
				'orientation' => 'O',
				'pdo' => 'O',
			),
			array(
				'id' => '6',
				'typeorient_id' => '5',
				'lib_struc' => 'CAF DE ROSNY SOUS BOIS',
				'num_voie' => '1',
				'type_voie' => 'AV',
				'nom_voie' => 'De la République',
				'code_postal' => '93110',
				'ville' => 'ROSNY SOUS BOIS',
				'code_insee' => '93064',
				'filtre_zone_geo' => null,
				'contratengagement' => 'O',
				'apre' => 'O',
				'orientation' => 'O',
				'pdo' => 'O',
			),
			array(
				'id' => '7',
				'typeorient_id' => '5',
				'lib_struc' => 'CAF DE SAINT DENIS',
				'num_voie' => '1',
				'type_voie' => 'AV',
				'nom_voie' => 'De la République',
				'code_postal' => '93200',
				'ville' => 'SAINT DENIS',
				'code_insee' => '93066',
				'filtre_zone_geo' => null,
				'contratengagement' => 'O',
				'apre' => 'O',
				'orientation' => 'O',
				'pdo' => 'O',
			)
		);
	}

?>
