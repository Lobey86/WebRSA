<?php
	class OrientsstructsController extends AppController
	{

		public $name = 'Orientsstructs';
		public $uses = array( 'Orientstruct',  'Option' , 'Dossier', 'Foyer', 'Adresse', 'Adressefoyer', 'Personne', 'Typeorient', 'Structurereferente', 'Pdf', 'Referent' );
		public $helpers = array( 'Default', 'Default2' );
		public $components = array( 'Gedooo' );

		public $commeDroit = array(
			'add' => 'Orientsstructs:edit'
		);

		protected function _setOptions() {
			$this->set( 'pays', $this->Option->pays() );
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'rolepers', $this->Option->rolepers() );
			$this->set( 'toppersdrodevorsa', $this->Option->toppersdrodevorsa() );
			$this->set( 'referents', $this->Referent->listOptions() );
			$this->set( 'typesorients', $this->Typeorient->listOptions() );
			$this->set( 'structs', $this->Structurereferente->list1Options( array( 'orientation' => 'O' ) ) );

			$options = array();
			$options = $this->Orientstruct->allEnumLists();
			$this->set( compact( 'options' ) );

			//Ajout des structures et référents orientants
			$this->set( 'refsorientants', $this->Referent->listOptions() );
			$this->set( 'structsorientantes', $this->Structurereferente->listOptions( array( 'orientation' => 'O' ) ) );

		}


		/**
		*
		*
		*
		*/

		public function beforeFilter() {
			$return = parent::beforeFilter();
			$options = array();
			foreach( $this->{$this->modelClass}->allEnumLists() as $field => $values ) {
				$options = Set::insert( $options, "{$this->modelClass}.{$field}", $values );
			}

			$this->set( compact( 'options' ) );

			return $return;
		}

		/**
		*
		*
		*
		*/

		public function index( $personne_id = null ){
			$this->assert( valid_int( $personne_id ), 'invalidParameter' );

			$orientstructs = $this->Orientstruct->find(
				'all',
				array(
					'conditions' => array(
						'Orientstruct.personne_id' => $personne_id
					),
// 					'recursive' => 0,
					'contain' => array(
						'Personne' => array(
							'Calculdroitrsa'
						),
						'Typeorient',
						'Structurereferente',
						'Referent'
					),
					'order' => array(
						'Orientstruct.rgorient DESC',
						'Orientstruct.date_valid DESC'
					)
				)
			);

			foreach( $orientstructs as $key => $orientstruct ) {
				$orientstruct[$this->Orientstruct->alias]['imprime'] = $this->Pdf->find(
					'count',
					array(
						'conditions' => array(
							'Pdf.fk_value' => $orientstruct[$this->Orientstruct->alias]['id'],
							'Pdf.modele = \'Orientstruct\''
						)
					)
				);
				$orientstructs[$key] = $orientstruct;
			}

			$dossier_id = Set::extract( $orientstructs, '0.Personne.Foyer.dossier_id' );

			$en_procedure_relance = false;
			if( Configure::read( 'Cg.departement' ) == 93 ) {
				// TODO: à déplacer dans un modèle à terme
				$en_procedure_relance = (
					$this->Orientstruct->Nonrespectsanctionep93->find(
						'count',
						array(
							'contain' => array(
								'Dossierep',
								'Orientstruct',
								'Contratinsertion',
								'Propopdo',
							),
							'conditions' => array(
								'OR' => array(
									array(
										'Dossierep.personne_id' => $personne_id,
										'Dossierep.etapedossierep <>' => 'traite'
									),
									array(
										'Nonrespectsanctionep93.active' => 1,
										'OR' => array(
											array(
												'Orientstruct.personne_id' => $personne_id,
												'Nonrespectsanctionep93.origine' => 'orientstruct'
											),
											array(
												'Contratinsertion.personne_id' => $personne_id,
												'Nonrespectsanctionep93.origine' => 'contratinsertion'
											),
											array(
												'Propopdo.personne_id' => $personne_id,
												'Nonrespectsanctionep93.origine' => 'pdo'
											)
										)
									),
								)
							)
						)
					) > 0
				);

				$reorientationep93 = $this->Orientstruct->Reorientationep93->find(
					'first',
					array(
						'contain' => array(
							'Orientstruct' => array(
								'fields' => array( 'rgorient' )
							),
							'Dossierep' => array(
								'fields' => array( 'etapedossierep' ),
								'Personne' => array(
									'fields' => array( 'nom', 'prenom' )
								)
							),
							'Typeorient' => array(
								'fields' => array( 'lib_type_orient' )
							),
							'Structurereferente' => array(
								'fields' => array( 'lib_struc' )
							),
						),
						'conditions' => array(
							'Dossierep.personne_id' => $personne_id,
							'Dossierep.themeep' => 'reorientationseps93',
							'Dossierep.etapedossierep <>' => 'traite'
						),
						'order' => array( 'Reorientationep93.created DESC' )
					)
				);
				$this->set( 'reorientationep93', $reorientationep93 );
				$this->set( 'optionsdossierseps', $this->Orientstruct->Reorientationep93->Dossierep->enums() );
			}

			$this->set( 'droitsouverts', $this->Dossier->Situationdossierrsa->droitsOuverts( $dossier_id ) );
			$this->set( 'orientstructs', $orientstructs );
			$this->set( 'en_procedure_relance', $en_procedure_relance );
			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );

			$this->set( 'rgorient_max', $this->Orientstruct->rgorientMax( $personne_id ) );
			if ( Configure::read( 'Cg.departement' ) == 58 && $this->Orientstruct->rgorientMax( $personne_id ) <=1 ) {
				$ajout_possible = $this->Orientstruct->Personne->Dossiercov58->ajoutPossible( $personne_id ) && $this->Orientstruct->ajoutPossible( $personne_id );
				$nbdossiersnonfinalisescovs = $this->Orientstruct->Personne->Dossiercov58->find(
					'count',
					array(
						'conditions' => array(
							'Dossiercov58.personne_id' => $personne_id,
							'Dossiercov58.etapecov <>' => 'finalise'
						),
						'joins' => array(
							array(
								'table' => 'proposorientationscovs58',
								'alias' => 'Propoorientationcov58',
								'type' => 'INNER',
								'conditions' => array(
									'Propoorientationcov58.dossiercov58_id = Dossiercov58.id'
								)
							)
						)
					)
				);
				$this->set( 'ajout_possible', $ajout_possible );
				$this->set( 'nbdossiersnonfinalisescovs', $nbdossiersnonfinalisescovs );
			}
			else {
				$this->set( 'ajout_possible', $this->Orientstruct->ajoutPossible( $personne_id ) );
			}
			$this->set( 'last_orientstruct_id', @$orientstructs[0]['Orientstruct']['id'] );
		}

		/**
		*
		*
		*
		*/

		public function add( $personne_id = null ) {
			$this->assert( valid_int( $personne_id ), 'invalidParameter' );

			// Retour à l'index en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			// Retour à l'index s'il n'est pas possible d'ajouter une orientation
			if( !$this->Orientstruct->ajoutPossible( $personne_id ) ) {
				$this->Session->setFlash( 'Impossible d\'ajouter une orientation pour cette personne.', 'flash/error' );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			// Pour le CG 93, les orientations de rang > 1 doivent passer en EP, donc il faut utiliser Reorientationseps93Controller::add
			// FIXME
			/*if( Configure::read( 'Cg.departement' ) == 93 && $this->Orientstruct->rgorientMax( $personne_id ) > 1 ) {
				$this->Session->setFlash( 'L\'orientation de cette personne doit se faire via un passage en EP', 'flash/error' );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}*/

			$dossier_id = $this->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Orientstruct->begin();
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Orientstruct->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

//             $this->set( 'options', $this->Typeorient->listOptions() );
//             $this->set( 'options2', $this->Structurereferente->list1Options( array( 'orientation' => 'O' ) ) );
//             $this->set( 'referents', $this->Referent->listOptions() );


			if( !empty( $this->data ) ) {
				$this->data['Orientstruct']['user_id'] = $this->Session->read( 'Auth.User.id' );

				$this->Orientstruct->set( $this->data );
//                 $this->Typeorient->set( $this->data );
//                 $this->Structurereferente->set( $this->data );

				$validates = $this->Orientstruct->validates();
//				$validates = $this->Typeorient->validates() && $validates;
//				$validates = $this->Structurereferente->validates() && $validates;

				if( $validates ) {
					$saved = true;

					/// FIXME: ne fonctionne que pour le cg58, à faire évoluer une fois la thématique mise en place
					if ( $this->Orientstruct->isRegression( $personne_id, $this->data['Orientstruct']['typeorient_id'] ) && /*( */Configure::read( 'Cg.departement' ) == 58 /*|| Configure::read( 'Cg.departement' ) == 93 )*/ ) {
						$theme = 'Regressionorientationep'.Configure::read( 'Cg.departement' );
						
						$dossierep = array(
							'Dossierep' => array(
								'personne_id' => $personne_id,
								'themeep' => Inflector::tableize( $theme )
							)
						);
						$saved = $this->Orientstruct->{$theme}->Dossierep->save( $dossierep ) && $saved;

						$regressionorientationep[$theme] = $this->data['Orientstruct'];
						$regressionorientationep[$theme]['personne_id'] = $personne_id;
						$regressionorientationep[$theme]['dossierep_id'] = $this->Orientstruct->{$theme}->Dossierep->id;

						if ( isset($regressionorientationep[$theme]['referent_id']) ) {
							list( $structurereferente_id, $referent_id ) = explode( '_', $regressionorientationep[$theme]['referent_id'] );
							$regressionorientationep[$theme]['structurereferente_id'] = $structurereferente_id;
							$regressionorientationep[$theme]['referent_id'] = $referent_id;
						}

						$regressionorientationep[$theme]['datedemande'] = $regressionorientationep[$theme]['date_propo'];

						$saved = $this->Orientstruct->{$theme}->save( $regressionorientationep ) && $saved;
					}
					else {
						// Correction: si la personne n'a pas encore d'entrée dans calculdroitsrsa
						$this->data['Calculdroitrsa']['personne_id'] = $personne_id;
						$this->data['Orientstruct']['personne_id'] = $personne_id;
						$this->data['Orientstruct']['valid_cg'] = true;
						$this->data['Orientstruct']['date_propo'] = date( 'Y-m-d' );
						$this->data['Orientstruct']['date_valid'] = date( 'Y-m-d' );
						$this->data['Orientstruct']['statut_orient'] = 'Orienté';

						$saved = $this->Orientstruct->Personne->Calculdroitrsa->save( $this->data );
						$saved = $this->Orientstruct->save( $this->data['Orientstruct'] ) && $saved;
					}

					if( $saved ) {
						$this->Jetons->release( $dossier_id );
						$this->Orientstruct->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $personne_id ) );
					}
					else {
						$this->Orientstruct->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
			}
			else {
				$personne = $this->Personne->findByid( $personne_id, null, null, 0 );
				$this->data['Calculdroitrsa'] = $personne['Calculdroitrsa'];
			}

			//$this->Orientstruct->commit();

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		*
		*/

		public function edit( $orientstruct_id = null ) {
			$this->assert( valid_int( $orientstruct_id ), 'invalidParameter' );

			// Retour à l'index en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$orientstruct_id = $this->Orientstruct->field( 'personne_id', array( 'id' => $orientstruct_id ) );
				$this->redirect( array( 'action' => 'index', $orientstruct_id ) );
			}

			$orientstruct = $this->Orientstruct->find(
				'first',
				array(
					'conditions' => array(
						'Orientstruct.id' => $orientstruct_id
					),
					'contain' => array(
						'Personne' => array(
							'Calculdroitrsa'
						)
					)
				)
			);
			$this->assert( !empty( $orientstruct ), 'invalidParameter' );

			// Retour à l'index si on essaie de modifier une autre orientation que la dernière
			if( !empty( $orientstruct['Orientstruct']['date_valid'] ) && $orientstruct['Orientstruct']['statut_orient'] == 'Orienté' && $orientstruct['Orientstruct']['rgorient'] != $this->Orientstruct->rgorientMax( $orientstruct['Orientstruct']['personne_id'] ) ) {
				$this->Session->setFlash( 'Impossible de modifier une autre orientation que la plus récente.', 'flash/error' );
				$this->redirect( array( 'action' => 'index', $orientstruct['Orientstruct']['personne_id'] ) );
			}

			$dossier_id = $this->Orientstruct->dossierId( $orientstruct_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Orientstruct->begin();
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Orientstruct->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

//             $this->set( 'options', $this->Typeorient->listOptions() );
//             $this->set( 'options2', $this->Structurereferente->list1Options( array( 'orientation' => 'O' ) ) );
//             $this->set( 'referents', $this->Referent->listOptions() );


			// Essai de sauvegarde
			if( !empty( $this->data ) ) {
				$this->data['Orientstruct']['user_id'] = $this->Session->read( 'Auth.User.id' );

				// Correction: si la personne n'a pas encore d'entrée dans calculdroitsrsa
				$this->data['Calculdroitrsa']['personne_id'] = $orientstruct['Orientstruct']['personne_id'];
				$this->data['Orientstruct']['personne_id'] = $orientstruct['Orientstruct']['personne_id'];

				$this->Orientstruct->set( $this->data );
				$this->Orientstruct->Personne->Calculdroitrsa->set( $this->data );
				$valid = $this->Orientstruct->Personne->Calculdroitrsa->validates();
				$valid = $this->Orientstruct->validates() && $valid;

				if( $valid ) {
					if( $this->Orientstruct->Personne->Calculdroitrsa->save( $this->data )
						&& $this->Orientstruct->save( $this->data )
					) {
						$this->Jetons->release( $dossier_id );
						$this->Orientstruct->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $orientstruct['Orientstruct']['personne_id'] ) );

					}
					else {
						$this->Orientstruct->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
			}
			// Afficage des données
			else {
				// Assignation au formulaire*
				$this->data = Set::merge( array( 'Orientstruct' => $orientstruct['Orientstruct'] ), array( 'Calculdroitrsa' => $orientstruct['Personne']['Calculdroitrsa'] ) );

			}

			$this->Orientstruct->commit();
			$this->_setOptions();
			$this->set( 'personne_id', $orientstruct['Orientstruct']['personne_id'] );
			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		* Impression d'une orientation simple
		*
		* - dans cohortes/orientees -> cohortes/impression_individuelle
		* - dans orientsstructs/index -> gedooos/orientstruct
		*/

		public function impression( $orientstruct_id = null ) {
			$this->assert( !empty( $orientstruct_id ), 'error404' );

			$pdf = $this->Orientstruct->getStoredPdf( $orientstruct_id, 'date_impression' );

			$this->assert( !empty( $pdf ), 'error404' );
			$this->assert( !empty( $pdf['Pdf']['document'] ), 'error500' ); // FIXME: ou en faire l'impression ?

			$this->Gedooo->sendPdfContentToClient( $pdf['Pdf']['document'], "{$orientstruct_id}.pdf" );
		}
	}
?>