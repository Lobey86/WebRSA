<?php
	/**
	 * Code source de la classe Cer93Test.
	 *
	 * PHP 5.3
	 *
	 * FIXME: Contratinsertion.dd_ci, Contratinsertion.df_ci, Contratinsertion.duree
	 *
	 * @package app.Test.Case.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Cer93Test réalise les tests unitaires de la classe Cer93.
	 *
	 * @package app.Test.Case.Model
	 */
	class Cer93Test extends CakeTestCase
	{
		/**
		 * Fixtures utilisées dans ce test unitaire.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'app.Adresse',
			'app.Adressefoyer',
			'app.Apre',
			'app.Contratinsertion',
			'app.Cer93',
			'app.Cer93Sujetcer93',
			'app.Compofoyercer93',
			'app.Diplomecer93',
			'app.Dossier',
			'app.Dsp',
			'app.DspRev',
			'app.Expprocer93',
			'app.Foyer',
			'app.Historiqueetatpe',
			'app.Informationpe',
			'app.Pdf',
			'app.Personne',
			'app.Prestation',
			'app.Referent',
			'app.Soussujetcer93',
			'app.Structurereferente',
			'app.Sujetcer93',
			'app.User',
		);

		/**
		 * Méthode exécutée avant chaque test.
		 *
		 * @return void
		 */
		public function setUp() {
			parent::setUp();
			$this->Cer93 = ClassRegistry::init( 'Cer93' );
		}

		/**
		 * Méthode exécutée après chaque test.
		 *
		 * @return void
		 */
		public function tearDown() {
			unset( $this->Cer93 );
		}

		/**
		 * Test de la méthode Cer93::prepareFormDataAddEdit().
		 *
		 * @group medium
		 * @return void
		 */
		public function testPrepareFormDataAddEditSansCerPrecedent() {
			$formData = $this->Cer93->prepareFormDataAddEdit( 1, null, 1  );

			$result = array(
				'Contratinsertion' => $formData['Contratinsertion'],
				'Cer93' => $formData['Cer93'],
				'Compofoyercer93' => $formData['Compofoyercer93'],
				'Diplomecer93' => $formData['Diplomecer93'],
				'Expprocer93' => $formData['Expprocer93'],
				'Sujetcer93' => $formData['Sujetcer93'],
			);

			$expected = array (
				'Contratinsertion' => array(
					'id' => NULL,
					'decision_ci' => 'E',
					'rg_ci' => NULL,
					'structurereferente_id' => 1,
				),
				'Cer93' => array(
					'id' => NULL,
					'contratinsertion_id' => NULL,
					'user_id' => 1,
					'nomutilisateur' => 'Dupont Jean',
					'structureutilisateur' => '« Projet de Ville RSA d\'Aubervilliers»',
					'matricule' => '123456700000000',
					'numdemrsa' => '66666666693',
					'rolepers' => 'DEM',
					'dtdemrsa' => '2009-09-01',
					'identifiantpe' => '0609065370Y',
					'qual' => 'MR',
					'nom' => 'BUFFIN',
					'nomnai' => 'BUFFIN',
					'prenom' => 'CHRISTIAN',
					'dtnai' => '1979-01-24',
					'adresse' => '66 Avenue DE LA REPUBLIQUE',
					'codepos' => '93300',
					'locaadr' => 'AUBERVILLIERS',
					'sitfam' => 'CEL',
					'natlog' => '0909',
					'inscritpe' => true,
					'nivetu' => '1202',
				),
				'Compofoyercer93' => array(
					array(
					  'qual' => 'MR',
					  'nom' => 'BUFFIN',
					  'prenom' => 'CHRISTIAN',
					  'dtnai' => '1979-01-24',
					  'rolepers' => 'DEM',
					),
				),
				'Diplomecer93' => array(),
				'Expprocer93' => array(),
				'Sujetcer93' => array()
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Cer93::prepareFormDataAddEdit() avec un CER précédent.
		 *
		 * @group medium
		 * @return void
		 */
		public function testPrepareFormDataAddEditAvecCerPrecedent() {
			$formData = $this->Cer93->prepareFormDataAddEdit( 2, null, 1  );

			$result = array(
				'Contratinsertion' => $formData['Contratinsertion'],
				'Cer93' => $formData['Cer93'],
				'Compofoyercer93' => $formData['Compofoyercer93'],
				'Diplomecer93' => $formData['Diplomecer93'],
				'Expprocer93' => $formData['Expprocer93'],
				'Sujetcer93' => $formData['Sujetcer93'],
			);

			$expected = array(
				'Contratinsertion' => array (
					'id' => NULL,
					'decision_ci' => 'E',
					'rg_ci' => NULL,
					'structurereferente_id' => 1,
				),
				'Cer93' => array(
					'id' => NULL,
					'matricule' => '987654321000000',
					'numdemrsa' => '77777777793',
					'rolepers' => 'DEM',
					'dtdemrsa' => '2010-07-12',
					'identifiantpe' => NULL, // FIXME
					'qual' => 'MME',
					'nom' => 'DURAND',
					'nomnai' => 'DUPUIS',
					'prenom' => 'JEANNE',
					'dtnai' => '1956-12-05',
					'adresse' => '120 Rue DU MARECHAL BROUILLON',
					'codepos' => '93230',
					'locaadr' => 'ROMAINVILLE',
					'sitfam' => 'MAR',
					'natlog' => NULL, // FIXME
					'inscritpe' => null,
					'contratinsertion_id' => NULL,
					'user_id' => 1,
					'nomutilisateur' => 'Dupont Jean',
					'structureutilisateur' => '« Projet de Ville RSA d\'Aubervilliers»',
					'incoherencesetatcivil' => 'Aucune incohérence',
					'cmu' => 'non',
					'cmuc' => 'encours',
					'nivetu' => '1203',
					'autresexps' => 'Autre expériences professionnelles',
				),
				'Compofoyercer93' => array(
					array(
						'qual' => 'MME',
						'nom' => 'DURAND',
						'prenom' => 'JEANNE',
						'dtnai' => '1956-12-05',
						'rolepers' => 'DEM',
					),
					array(
						'qual' => 'MR',
						'nom' => 'DURAND',
						'prenom' => 'RAOUL',
						'dtnai' => '1950-05-07',
						'rolepers' => 'CJT',
					),
				),
				'Diplomecer93' => array(
					array(
						'name' => 'Diplôme de soudeur',
						'annee' => '2005',
						'created' => '2012-10-01 15:36:00',
						'modified' => '2012-10-01 15:36:00',
					),
					array(
						'name' => 'Diplôme de manutentionnaire',
						'annee' => '2003',
						'created' => '2012-10-01 15:36:00',
						'modified' => '2012-10-01 15:36:00',
					),
				),
				'Expprocer93' => array(
					array(
						'metierexerce_id' => 2,
						'secteuracti_id' => 2,
						'anneedeb' => 2007,
						'duree' => '9 mois',
						'created' => '2012-10-01 15:36:00',
						'modified' => '2012-10-01 15:36:00',
					),
					array(
						'metierexerce_id' => 1,
						'secteuracti_id' => 2,
						'anneedeb' => 2005,
						'duree' => '3 mois',
						'created' => '2012-10-01 15:36:00',
						'modified' => '2012-10-01 15:36:00',
					),
				),
				'Sujetcer93' => array(
					array(
						'name' => 'Sujet 1',
						'isautre' => '0',
						'created' => NULL,
						'modified' => NULL,
						'Cer93Sujetcer93' =>
						array(
							'sujetcer93_id' => 1,
							'soussujetcer93_id' => 1,
							'commentaireautre' => NULL,
							'created' => '2012-10-01 15:36:00',
							'modified' => '2012-10-01 15:36:00',
						),
					),
					array(
						'name' => 'Sujet 3',
						'isautre' => '1',
						'created' => NULL,
						'modified' => NULL,
						'Cer93Sujetcer93' =>
						array(
							'sujetcer93_id' => 3,
							'soussujetcer93_id' => NULL,
							'commentaireautre' => 'Commentaire autre',
							'created' => '2012-10-01 15:36:00',
							'modified' => '2012-10-01 15:36:00',
						),
					),
				)
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * TODO:
		 * Test de la méthode Cer93::prepareFormDataAddEdit() avec une modification de CER.
		 *
		 * @group medium
		 * @return void
		 */
		public function testPrepareFormDataAddEditAvecModificationCer() {
			$formData = $this->Cer93->prepareFormDataAddEdit( 1, 2, 1  );

			$result = array(
				'Contratinsertion' => $formData['Contratinsertion'],
				'Cer93' => $formData['Cer93'],
				'Compofoyercer93' => $formData['Compofoyercer93'],
				'Diplomecer93' => $formData['Diplomecer93'],
				'Expprocer93' => $formData['Expprocer93'],
				'Sujetcer93' => $formData['Sujetcer93'],
			);

			$expected = array(
				'Contratinsertion' => array(
					'id' => 2,
					'personne_id' => 1,
					'rg_ci' => NULL,
					'decision_ci' => 'E',
				),
				'Cer93' => array(
					'id' => 2,
					'contratinsertion_id' => 2,
					'user_id' => 1,
					'matricule' => '123456700000000',
					'dtdemrsa' => '2009-09-01',
					'qual' => 'MR',
					'nom' => 'BUFFIN',
					'nomnai' => 'BUFFIN',
					'prenom' => 'CHRISTIAN',
					'dtnai' => '1979-01-24',
					'adresse' => '66 Avenue DE LA REPUBLIQUE',
					'codepos' => '93300',
					'locaadr' => 'AUBERVILLIERS',
					'sitfam' => 'CEL',
					'natlog' => '0909',
					'incoherencesetatcivil' => 'Aucune incohérence',
					'inscritpe' => true,
					'cmu' => 'non',
					'cmuc' => 'encours',
					'nivetu' => '1203',
					'numdemrsa' => '66666666693',
					'rolepers' => 'DEM',
					'identifiantpe' => '0609065370Y',
					'positioncer' => '02attdecisioncpdv',
					'formeci' => NULL,
					'datesignature' => NULL,
					'autresexps' => 'Autre expériences professionnelles',
					'isemploitrouv' => 'O',
					'metierexerce_id' => 1,
					'secteuracti_id' => 2,
					'naturecontrat_id' => 3,
					'dureehebdo' => 35,
					'dureecdd' => '1',
					'prevu' => '',
					'bilancerpcd' => NULL,
					'duree' => 1,
					'pointparcours' => 'aladate',
					'datepointparcours' => '2010-12-31',
					'pourlecomptede' => 'JACQUES ANTOINE',
					'observpro' => 'Observations du professionnel',
					'observbenef' => 'Obsrevations du bénéficiaire',
					'structureutilisateur' => '« Projet de Ville RSA d\'Aubervilliers»',
					'nomutilisateur' => 'Dupont Jean',
					'created' => '2010-10-25 11:00:00',
					'modified' => '2010-10-25 11:45:00',
					'Diplomecer93' => array(
						array(
							'id' => 4,
							'cer93_id' => 2,
							'name' => 'Diplôme d\'informatique',
							'annee' => 2005,
							'created' => '2012-10-01 15:36:00',
							'modified' => '2012-10-01 15:36:00',
						),
						array(
							'id' => 3,
							'cer93_id' => 2,
							'name' => 'BAC',
							'annee' => 2001,
							'created' => '2012-10-01 15:36:00',
							'modified' => '2012-10-01 15:36:00',
						),
					),
					'Expprocer93' => array(
						array(
							'id' => 3,
							'cer93_id' => 2,
							'metierexerce_id' => 2,
							'secteuracti_id' => 2,
							'anneedeb' => 2009,
							'duree' => '6 mois',
							'created' => '2012-10-01 15:36:00',
							'modified' => '2012-10-01 15:36:00',
						),
						array(
							'id' => 4,
							'cer93_id' => 2,
							'metierexerce_id' => 1,
							'secteuracti_id' => 1,
							'anneedeb' => 2005,
							'duree' => '3 ans',
							'created' => '2012-10-01 15:36:00',
							'modified' => '2012-10-01 15:36:00',
						),
					),
					'Sujetcer93' => array(
						array(
							'id' => 2,
							'name' => 'Sujet 2',
							'isautre' => '0',
							'created' => NULL,
							'modified' => NULL,
							'Cer93Sujetcer93' =>
							array(
								'id' => 3,
								'cer93_id' => 2,
								'sujetcer93_id' => 2,
								'soussujetcer93_id' => 2,
								'commentaireautre' => NULL,
								'created' => '2012-10-01 15:36:00',
								'modified' => '2012-10-01 15:36:00',
							),
						),
						array(
							'id' => 3,
							'name' => 'Sujet 3',
							'isautre' => '1',
							'created' => NULL,
							'modified' => NULL,
							'Cer93Sujetcer93' =>
							array(
								'id' => 4,
								'cer93_id' => 2,
								'sujetcer93_id' => 3,
								'soussujetcer93_id' => NULL,
								'commentaireautre' => 'Commentaire autre',
								'created' => '2012-10-01 15:36:00',
								'modified' => '2012-10-01 15:36:00',
							),
						),
					),
				),
				'Compofoyercer93' => array(
					array(
						'qual' => 'MR',
						'nom' => 'BUFFIN',
						'prenom' => 'CHRISTIAN',
						'dtnai' => '1979-01-24',
						'rolepers' => 'DEM',
					),
				),
				'Diplomecer93' => array(
					array(
						'id' => 4,
						'cer93_id' => 2,
						'name' => 'Diplôme d\'informatique',
						'annee' => 2005,
						'created' => '2012-10-01 15:36:00',
						'modified' => '2012-10-01 15:36:00',
					),
					array(
						'id' => 3,
						'cer93_id' => 2,
						'name' => 'BAC',
						'annee' => 2001,
						'created' => '2012-10-01 15:36:00',
						'modified' => '2012-10-01 15:36:00',
					),
				),
				'Expprocer93' => array(
					array(
						'id' => 3,
						'cer93_id' => 2,
						'metierexerce_id' => 2,
						'secteuracti_id' => 2,
						'anneedeb' => 2009,
						'duree' => '6 mois',
						'created' => '2012-10-01 15:36:00',
						'modified' => '2012-10-01 15:36:00',
					),
					array(
						'id' => 4,
						'cer93_id' => 2,
						'metierexerce_id' => 1,
						'secteuracti_id' => 1,
						'anneedeb' => 2005,
						'duree' => '3 ans',
						'created' => '2012-10-01 15:36:00',
						'modified' => '2012-10-01 15:36:00',
					),
				),
				'Sujetcer93' => array(
					array(
						'id' => 2,
						'name' => 'Sujet 2',
						'isautre' => '0',
						'created' => NULL,
						'modified' => NULL,
						'Cer93Sujetcer93' =>
						array(
							'id' => 3,
							'cer93_id' => 2,
							'sujetcer93_id' => 2,
							'soussujetcer93_id' => 2,
							'commentaireautre' => NULL,
							'created' => '2012-10-01 15:36:00',
							'modified' => '2012-10-01 15:36:00',
						),
					),
					array(
						'id' => 3,
						'name' => 'Sujet 3',
						'isautre' => '1',
						'created' => NULL,
						'modified' => NULL,
						'Cer93Sujetcer93' =>
						array(
							'id' => 4,
							'cer93_id' => 2,
							'sujetcer93_id' => 3,
							'soussujetcer93_id' => NULL,
							'commentaireautre' => 'Commentaire autre',
							'created' => '2012-10-01 15:36:00',
							'modified' => '2012-10-01 15:36:00',
						),
					),
				),
			);

			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Cer93::saveFormulaire().
		 *
		 * @group medium
		 * @return void
		 */
		public function testSaveFormulaire() {
			$data = array(
				'Contratinsertion' => array(
					'id' => '',
					'personne_id' => '1',
					'rg_ci' => '1',
					'structurereferente_id' => '1',
					'referent_id' => '1_1',
					'dd_ci' => array(
						'day' => '01',
						'month' => '11',
						'year' => '2012',
					),
					'df_ci' => array(
						'day' => '28',
						'month' => '02',
						'year' => '2013',
					),
					'date_saisi_ci' => array(
						'day' => '24',
						'month' => '10',
						'year' => '2012',
					),
					'haspiecejointe' => '0',
				),
				'Cer93' => array(
					'id' => '',
					'contratinsertion_id' => '',
					'rolepers' => 'DEM',
					'numdemrsa' => '66666666693',
					'identifiantpe' => null,
					'user_id' => '1',
					'nomutilisateur' => 'DUPONT Robert',
					'structureutilisateur' => '« Projet de Ville RSA d\'Aubervilliers»',
					'matricule' => '123456700000000',
					'dtdemrsa' => '2009-06-01',
					'qual' => 'MR',
					'nom' => 'BUFFIN',
					'nomnai' => 'BUFFIN',
					'prenom' => 'CHRISTIAN',
					'dtnai' => '1979-01-24',
					'adresse' => '66 AVENUE DE LA REPUBLIQUE',
					'codepos' => '93300',
					'locaadr' => 'AUBERVILLIERS',
					'sitfam' => 'CEL',
					'natlog' => '',
					'incoherencesetatcivil' => 'Incohérence',
					'inscritpe' => '1',
					'cmu' => 'oui',
					'cmuc' => 'oui',
					'nivetu' => '1205',
					'autresexps' => 'Autre exp.',
					'isemploitrouv' => 'O',
					'secteuracti_id' => '1',
					'metierexerce_id' => '2',
					'dureehebdo' => '35',
					'naturecontrat_id' => '1',
					'dureecdd' => 'DT2',
					'bilancerpcd' => 'Bilan cer pcd.',
					'duree' => '9',
					'pointparcours' => 'aladate',
					'datepointparcours' => array(
						'day' => '01',
						'month' => '02',
						'year' => '2013',
					),
					'pourlecomptede' => 'AAAPDVAUBERVILLIERS',
					'observpro' => 'Observations',
				),
				'Personne' => array(
					'sexe' => '1',
				),
				'Compofoyercer93' => array(
					array(
						'id' => '',
						'cer93_id' => '',
						'qual' => 'MR',
						'nom' => 'BUFFIN',
						'prenom' => 'CHRISTIAN',
						'dtnai' => '1979-01-24',
						'rolepers' => 'DEM',
					),
				),
				'Diplomecer93' => array(
					array(
						'id' => '',
						'cer93_id' => '',
						'name' => 'Diplôme d\'informatique',
						'annee' => '2000',
					),
				),
				'Expprocer93' => array(
					array(
						'id' => '',
						'cer93_id' => '',
						'metierexerce_id' => '1',
						'secteuracti_id' => '1',
						'anneedeb' => '2010',
						'duree' => '3 mois',
					),
				),
				'Sujetcer93' => array(
					'Sujetcer93' => array(
						array(
							'sujetcer93_id' => 1,
							'soussujetcer93_id' => 1,
							'commentaireautre' => null,
						),
						array(
							'sujetcer93_id' => 2,
							'soussujetcer93_id' => null,
						),
					),
				),
			);

			$result = $this->Cer93->saveFormulaire( $data );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Cer93->find(
				'first',
				array(
					'conditions' => array(
						'Cer93.id' => $this->Cer93->id
					),
					'contain' => array(
						'Contratinsertion',
						'Compofoyercer93',
						'Diplomecer93',
						'Expprocer93',
						'Sujetcer93',
					)
				)
			);

			$expected = array(
				'Cer93' => array(
					'id' => 3,
					'contratinsertion_id' => 3,
					'user_id' => 1,
					'matricule' => '123456700000000',
					'dtdemrsa' => '2009-06-01',
					'qual' => 'MR',
					'nom' => 'BUFFIN',
					'nomnai' => 'BUFFIN',
					'prenom' => 'CHRISTIAN',
					'dtnai' => '1979-01-24',
					'adresse' => '66 AVENUE DE LA REPUBLIQUE',
					'codepos' => '93300',
					'locaadr' => 'AUBERVILLIERS',
					'sitfam' => 'CEL',
					'natlog' => NULL,
					'incoherencesetatcivil' => 'Incohérence',
					'inscritpe' => '1',
					'cmu' => 'oui',
					'cmuc' => 'oui',
					'nivetu' => '1205',
					'numdemrsa' => '66666666693',
					'rolepers' => 'DEM',
					'identifiantpe' => NULL,
					'positioncer' => '00enregistre',
					'formeci' => NULL,
					'datesignature' => NULL,
					'autresexps' => 'Autre exp.',
					'isemploitrouv' => 'O',
					'metierexerce_id' => 2,
					'secteuracti_id' => 1,
					'naturecontrat_id' => 1,
					'dureehebdo' => 35,
					'dureecdd' => 'DT2',
					'prevu' => '',
					'bilancerpcd' => 'Bilan cer pcd.',
					'duree' => 9,
					'pointparcours' => 'aladate',
					'datepointparcours' => '2013-02-01',
					'pourlecomptede' => 'AAAPDVAUBERVILLIERS',
					'observpro' => 'Observations',
					'observbenef' => '',
					'structureutilisateur' => '« Projet de Ville RSA d\'Aubervilliers»',
					'nomutilisateur' => 'DUPONT Robert',
				),
				'Contratinsertion' => array(
					'id' => 3,
					'personne_id' => 1,
					'structurereferente_id' => 1,
					'typocontrat_id' => NULL,
					'dd_ci' => '2012-11-01',
					'df_ci' => '2013-02-28',
					'diplomes' => '',
					'form_compl' => '',
					'expr_prof' => '',
					'aut_expr_prof' => '',
					'rg_ci' => 1,
					'actions_prev' => '',
					'obsta_renc' => '',
					'service_soutien' => '',
					'pers_charg_suivi' => '',
					'objectifs_fixes' => '',
					'engag_object' => '',
					'sect_acti_emp' => '',
					'emp_occupe' => '',
					'duree_hebdo_emp' => '',
					'nat_cont_trav' => '',
					'duree_cdd' => '',
					'duree_engag' => NULL,
					'nature_projet' => '',
					'observ_ci' => '',
					'decision_ci' => 'E',
					'datevalidation_ci' => NULL,
					'date_saisi_ci' => '2012-10-24',
					'lieu_saisi_ci' => '',
					'emp_trouv' => false,
					'forme_ci' => '',
					'commentaire_action' => '',
					'raison_ci' => '',
					'aviseqpluri' => '',
					'sitfam_ci' => '',
					'sitpro_ci' => '',
					'observ_benef' => '',
					'referent_id' => 1,
					'avisraison_ci' => '',
					'type_demande' => NULL,
					'num_contrat' => NULL,
					'typeinsertion' => NULL,
					'bilancontrat' => '',
					'engag_object_referent' => '',
					'outilsmobilises' => '',
					'outilsamobiliser' => '',
					'niveausalaire' => NULL,
					'zonegeographique_id' => NULL,
					'autreavisradiation' => NULL,
					'autreavissuspension' => NULL,
					'datesuspensionparticulier' => NULL,
					'dateradiationparticulier' => NULL,
					'faitsuitea' => NULL,
					'positioncer' => 'attvalid',
					'current_action' => '',
					'haspiecejointe' => '0',
					'avenant_id' => NULL,
					'sitfam' => '',
					'typeocclog' => '',
					'persacharge' => '',
					'objetcerprecautre' => '',
					'motifannulation' => '',
					'datedecision' => NULL,
					'datenotification' => NULL,
					'actioncandidat_id' => NULL,
					'nbjours' => number_format( ceil( ( time() - strtotime( '2013-02-28' ) ) / ( 60 * 60 * 24 ) ) ),
					'present' => true,
				),
				'Compofoyercer93' => array(
					array(
						'id' => 3,
						'cer93_id' => 3,
						'qual' => 'MR',
						'nom' => 'BUFFIN',
						'prenom' => 'CHRISTIAN',
						'dtnai' => '1979-01-24',
						'rolepers' => 'DEM'
					),
				),
				'Diplomecer93' => array(
					array(
						'id' => 5,
						'cer93_id' => 3,
						'name' => 'Diplôme d\'informatique',
						'annee' => 2000,
					),
				),
				'Expprocer93' => array(
					array(
						'id' => 5,
						'cer93_id' => 3,
						'metierexerce_id' => 1,
						'secteuracti_id' => 1,
						'anneedeb' => 2010,
						'duree' => '3 mois',
					),
				),
				'Sujetcer93' => array(
					array(
						'id' => 1,
						'name' => 'Sujet 1',
						'Cer93Sujetcer93' => array(
							'id' => 5,
							'cer93_id' => 3,
							'sujetcer93_id' => 1,
							'soussujetcer93_id' => 1,
							'commentaireautre' => null,
						),
						'isautre' => '0'
					),
					array(
						'id' => 2,
						'name' => 'Sujet 2',
						'Cer93Sujetcer93' => array(
							'id' => 6,
							'cer93_id' => 3,
							'sujetcer93_id' => 2,
							'soussujetcer93_id' => NULL,
							'commentaireautre' => null,
						),
						'isautre' => '0'
					),
				),
			);

			// TODO: en faire une fonction
			foreach( Set::flatten( $result ) as $path => $value ) {
				if( preg_match( '/\.(created|modified)/', $path ) ) {
					$result = Set::remove( $result, $path );
				}
			}

			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>