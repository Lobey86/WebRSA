<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class OrientstructFixture extends CakeAppTestFixture {

		var $name = 'Orientstruct';
		var $table = 'orientsstructs';
		var $import = array( 'table' => 'orientsstructs', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'personne_id' => '2',
				'typeorient_id' => '1',
				'structurereferente_id' => '1',
				'propo_algo' => 1,
				'valid_cg' => null,
				'date_propo' => '2009-03-10',
				'date_valid' => '2009-03-10',
				'statut_orient' => 'Orienté',
				'date_impression' => '2010-06-03',
				'daterelance' => null,
				'statutrelance' => 'E',
				'date_impression_relance' => null,
				'referent_id' => '1',
				'etatorient' => null,
				'rgorient' => null,
				'structureorientante_id' => null,
				'referentorientant_id' => null,
			),
			array(
				'id' => '2',
				'personne_id' => '1',
				'typeorient_id' => '1',
				'structurereferente_id' => '1',
				'propo_algo' => 3,
				'valid_cg' => '1',
				'date_propo' => '2010-08-09',
				'date_valid' => '2010-08-09',
				'statut_orient' => 'Orienté',
				'date_impression' => '2009-01-21',
				'daterelance' => null,
				'statutrelance' => 'E',
				'date_impression_relance' => null,
				'referent_id' => '1',
				'etatorient' => null,
				'rgorient' => null,
				'structureorientante_id' => null,
				'referentorientant_id' => null,			
			),
			array(
				'id' => '3',
				'personne_id' => '3',
				'typeorient_id' => '1',
				'structurereferente_id' => '1',
				'propo_algo' => 3,
				'valid_cg' => '1',
				'date_propo' => '2009-01-09',
				'date_valid' => '2009-01-09',
				'statut_orient' => 'Orienté',
				'date_impression' => '2010-04-19',
				'daterelance' => null,
				'statutrelance' => 'E',
				'date_impression_relance' => null,
				'referent_id' => '1',
				'etatorient' => null,
				'rgorient' => null,
				'structureorientante_id' => null,
				'referentorientant_id' => null,
			),
			array(
				'id' => '4',
				'personne_id' => '4',
				'typeorient_id' => '1',
				'structurereferente_id' => '1',
				'propo_algo' => 2,
				'valid_cg' => '1',
				'date_propo' => '2009-03-09',
				'date_valid' => '2009-03-09',
				'statut_orient' => 'Orienté',
				'date_impression' => null,
				'daterelance' => null,
				'statutrelance' => 'E',
				'date_impression_relance' => null,
				'referent_id' => '1',
				'etatorient' => null,
				'rgorient' => null,
				'structureorientante_id' => null,
				'referentorientant_id' => null,
			),
			array(
				'id' => '5',
				'personne_id' => '5',
				'typeorient_id' => '1',
				'structurereferente_id' => '1',
				'propo_algo' => 1,
				'valid_cg' => '1',
				'date_propo' => '2009-07-09',
				'date_valid' => '2009-07-09',
				'statut_orient' => 'Orienté',
				'date_impression' => '2009-06-12',
				'daterelance' => null,
				'statutrelance' => 'E',
				'date_impression_relance' => null,
				'referent_id' => '1',
				'etatorient' => null,
				'rgorient' => null,
				'structureorientante_id' => null,
				'referentorientant_id' => null,
			),
			array(
				'id' => '6',
				'personne_id' => '6',
				'typeorient_id' => '1',
				'structurereferente_id' => '1',
				'propo_algo' => 1,
				'valid_cg' => '1',
				'date_propo' => '2010-07-09',
				'date_valid' => '2010-07-09',
				'statut_orient' => 'Orienté',
				'date_impression' => '2009-11-12',
				'daterelance' => null,
				'statutrelance' => 'E',
				'date_impression_relance' => null,
				'referent_id' => '1',
				'etatorient' => null,
				'rgorient' => null,
				'structureorientante_id' => null,
				'referentorientant_id' => null,
			),
			array(
				'id' => '7',
				'personne_id' => '7',
				'typeorient_id' => '1',
				'structurereferente_id' => '1',
				'propo_algo' => 1,
				'valid_cg' => '1',
				'date_propo' => '2010-11-09',
				'date_valid' => '2010-11-09',
				'statut_orient' => 'Orienté',
				'date_impression' => '2009-02-21',
				'daterelance' => null,
				'statutrelance' => 'E',
				'date_impression_relance' => null,
				'referent_id' => '1',
				'etatorient' => null,
				'rgorient' => null,
				'structureorientante_id' => null,
				'referentorientant_id' => null,
			),
			array(
				'id' => '8',
				'personne_id' => '8',
				'typeorient_id' => '1',
				'structurereferente_id' => '1',
				'propo_algo' => 2,
				'valid_cg' => '1',
				'date_propo' => '2009-01-09',
				'date_valid' => '2009-01-09',
				'statut_orient' => 'Orienté',
				'date_impression' => null,
				'daterelance' => null,
				'statutrelance' => 'E',
				'date_impression_relance' => null,
				'referent_id' => '1',
				'etatorient' => null,
				'rgorient' => null,
				'structureorientante_id' => null,
				'referentorientant_id' => null,
			),
			array(
				'id' => '9',
				'personne_id' => '9',
				'typeorient_id' => '1',
				'structurereferente_id' => '1',
				'propo_algo' => 1,
				'valid_cg' => '1',
				'date_propo' => '2009-01-09',
				'date_valid' => '2009-01-09',
				'statut_orient' => 'Orienté',
				'date_impression' => '2010-06-19',
				'daterelance' => null,
				'statutrelance' => 'E',
				'date_impression_relance' => null,
				'referent_id' => '1',
				'etatorient' => null,
				'rgorient' => null,
				'structureorientante_id' => null,
				'referentorientant_id' => null,
			),
			array(
				'id' => '1001',
				'personne_id' => '1001',
				'typeorient_id' => '5',
				'structurereferente_id' => '7',
				'propo_algo' => 1,
				'valid_cg' => null,
				'date_propo' => '2009-03-10',
				'date_valid' => '2009-03-10',
				'statut_orient' => 'Orienté',
				'date_impression' => '2010-06-03',
				'daterelance' => null,
				'statutrelance' => 'E',
				'date_impression_relance' => null,
				'referent_id' => null,
				'etatorient' => null,
				'rgorient' => '1',
				'structureorientante_id' => null,
				'referentorientant_id' => null,
			),
			array(
				'id' => '2002',
				'personne_id' => '2002',
				'typeorient_id' => '5',
				'structurereferente_id' => '7',
				'propo_algo' => 1,
				'valid_cg' => null,
				'date_propo' => '2009-03-10',
				'date_valid' => '2009-03-10',
				'statut_orient' => 'Orienté',
				'date_impression' => '2010-06-03',
				'daterelance' => null,
				'statutrelance' => 'E',
				'date_impression_relance' => null,
				'referent_id' => null,
				'etatorient' => null,
				'rgorient' => '1',
				'structureorientante_id' => null,
				'referentorientant_id' => null,
			),
			array(
				'id' => '3003',
				'personne_id' => '3003',
				'typeorient_id' => '5',
				'structurereferente_id' => '7',
				'propo_algo' => 1,
				'valid_cg' => null,
				'date_propo' => '2009-03-10',
				'date_valid' => '2009-03-10',
				'statut_orient' => 'Orienté',
				'date_impression' => '2010-06-03',
				'daterelance' => null,
				'statutrelance' => 'E',
				'date_impression_relance' => null,
				'referent_id' => null,
				'etatorient' => null,
				'rgorient' => '1',
				'structureorientante_id' => null,
				'referentorientant_id' => null,
			),
			array(
				'id' => '4004',
				'personne_id' => '4004',
				'typeorient_id' => '5',
				'structurereferente_id' => '1',
				'propo_algo' => 1,
				'valid_cg' => null,
				'date_propo' => '2009-03-10',
				'date_valid' => '2009-03-10',
				'statut_orient' => 'Orienté',
				'date_impression' => '2010-06-03',
				'daterelance' => null,
				'statutrelance' => 'R',
				'date_impression_relance' => null,
				'referent_id' => null,
				'etatorient' => null,
				'rgorient' => '1',
				'structureorientante_id' => null,
				'referentorientant_id' => null,
			),
		);
	}

?>
