<?php
	App::import( 'Helper', 'Locale' );

	class RendezvousController extends AppController
	{

		public $name = 'Rendezvous';
		public $uses = array( 'Rendezvous', 'Option' );
		public $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Default2', 'Fileuploader' );
		public $components = array( 'Gedooo.Gedooo', 'Fileuploader', 'Jetons2' );
		public $commeDroit = array(
			'view' => 'Rendezvous:index',
			'add' => 'Rendezvous:edit'
		);
		public $aucunDroit = array( 'ajaxreferent', 'ajaxreffonct', 'ajaxperm', 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download' );

		/**
		 *
		 */
		protected function _setOptions() {
			$this->set( 'struct', $this->Rendezvous->Structurereferente->listOptions() );
			$this->set( 'permanences', $this->Rendezvous->Permanence->listOptions() );
			$this->set( 'statutrdv', $this->Rendezvous->Statutrdv->find( 'list' ) );
			$options = $this->Rendezvous->allEnumLists();
			$this->set( 'options', $options );
		}

		/**
		 *   Ajax pour les coordonnées du référent APRE
		 */
		public function ajaxreffonct( $referent_id = null ) { // FIXME
			Configure::write( 'debug', 0 );

			if( !empty( $referent_id ) ) {
				$referent_id = suffix( $referent_id );
			}
			else {
				$referent_id = suffix( Set::extract( $this->request->data, 'Rendezvous.referent_id' ) );
			}

			$referent = array( );
			if( !empty( $referent_id ) ) {
				$qd_referent = array(
					'conditions' => array(
						'Referent.id' => $referent_id
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$referent = $this->Rendezvous->Referent->find( 'first', $qd_referent );
			}

			$this->set( 'referent', $referent );
			$this->render( 'ajaxreffonct', 'ajax' );
		}

		/**
		 * http://valums.com/ajax-upload/
		 * http://doc.ubuntu-fr.org/modules_php
		 * increase post_max_size and upload_max_filesize to 10M
		 * debug( array( ini_get( 'post_max_size' ), ini_get( 'upload_max_filesize' ) ) ); -> 10M
		 */
		public function ajaxfileupload() {
			$this->Fileuploader->ajaxfileupload();
		}

		/**
		 * http://valums.com/ajax-upload/
		 * http://doc.ubuntu-fr.org/modules_php
		 * increase post_max_size and upload_max_filesize to 10M
		 * debug( array( ini_get( 'post_max_size' ), ini_get( 'upload_max_filesize' ) ) ); -> 10M
		 * FIXME: traiter les valeurs de retour
		 */
		public function ajaxfiledelete() {
			$this->Fileuploader->ajaxfiledelete();
		}

		/**
		 *   Fonction permettant de visualiser les fichiers chargés dans la vue avant leur envoi sur le serveur
		 */
		public function fileview( $id ) {
			$this->Fileuploader->fileview( $id );
		}

		/**
		 *   Téléchargement des fichiers préalablement associés à un traitement donné
		 */
		public function download( $fichiermodule_id ) {
			$this->assert( !empty( $fichiermodule_id ), 'error404' );
			$this->Fileuploader->download( $fichiermodule_id );
		}

		/**
		 *   Fonction permettant d'accéder à la page pour lier les fichiers à l'Orientation
		 */
		public function filelink( $id ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$fichiers = array( );
			$rendezvous = $this->Rendezvous->find(
				'first',
				array(
					'conditions' => array(
						'Rendezvous.id' => $id
					),
					'contain' => array(
						'Fichiermodule' => array(
							'fields' => array( 'name', 'id', 'created', 'modified' )
						)
					)
				)
			);

			$personne_id = $rendezvous['Rendezvous']['personne_id'];
			$dossier_id = $this->Rendezvous->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->request->data ) ) {
				$this->Rendezvous->begin();

				$saved = $this->Rendezvous->updateAll(
					array( 'Rendezvous.haspiecejointe' => '\''.$this->request->data['Rendezvous']['haspiecejointe'].'\'' ),
					array(
						'"Rendezvous"."personne_id"' => $personne_id,
						'"Rendezvous"."id"' => $id
					)
				);

				if( $saved ) {
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->request->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->request->data, "Rendezvous.haspiecejointe" ), $id ) && $saved;
				}

				if( $saved ) {
					$this->Rendezvous->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
// 					$this->redirect( array(  'controller' => 'rendezvous','action' => 'index', $personne_id ) );
					$this->redirect( $this->referer() );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->Rendezvous->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			$this->_setOptions();
			$this->set( 'urlmenu', '/rendezvous/index/'.$personne_id );
			$this->set( compact( 'dossier_id', 'personne_id', 'fichiers', 'rendezvous' ) );
		}

		/**
		 *
		 */
		public function index( $personne_id = null ) {
			$this->Rendezvous->Personne->unbindModelAll();
			$nbrPersonnes = $this->Rendezvous->Personne->find(
				'count',
				array(
					'conditions' => array(
						'Personne.id' => $personne_id
					),
					'contain' => false
				)
			);
			$this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );

			$this->Rendezvous->forceVirtualFields = true;
			$rdvs = $this->Rendezvous->find(
				'all',
				array(
					'fields' => array(
						'Rendezvous.id',
						'Rendezvous.personne_id',
						'Personne.nom_complet',
						'Structurereferente.lib_struc',
						'Referent.nom_complet',
						'Permanence.libpermanence',
						'Typerdv.libelle',
						'Statutrdv.libelle',
						'Rendezvous.daterdv',
						'Rendezvous.heurerdv',
						'Rendezvous.objetrdv',
						'Rendezvous.commentairerdv',
						'StatutrdvTyperdv.motifpassageep',
						$this->Rendezvous->Fichiermodule->sqNbFichiersLies( $this->Rendezvous, 'nb_fichiers_lies' )
					),
					'joins' => array(
						$this->Rendezvous->join( 'Personne' ),
						$this->Rendezvous->join( 'Structurereferente' ),
						$this->Rendezvous->join( 'Referent' ),
						$this->Rendezvous->join( 'Statutrdv' ),
						$this->Rendezvous->join( 'Permanence' ),
						$this->Rendezvous->join( 'Typerdv' ),
						$this->Rendezvous->Typerdv->join( 'StatutrdvTyperdv' )
					),
					'contain' => false,
					'conditions' => array(
						'Rendezvous.personne_id' => $personne_id
					),
					'order' => array(
						'Rendezvous.daterdv DESC',
						'Rendezvous.heurerdv DESC'
					)
				)
			);

			if( isset( $rdvs['0']['Rendezvous']['id'] ) && !empty( $rdvs['0']['Rendezvous']['id'] ) ) {
				$lastrdv_id = $rdvs['0']['Rendezvous']['id'];
			}
			else {
				$lastrdv_id = 0;
			}
			$this->set( 'lastrdv_id', $lastrdv_id );
			$this->Rendezvous->forceVirtualFields = false;

			if( Configure::read( 'Cg.departement' ) == 58 ) {
				$dossierep = $this->Rendezvous->Personne->Dossierep->find(
					'first',
					array(
						'fields' => array(
							'StatutrdvTyperdv.motifpassageep',
						),
						'joins' => array(
							$this->Rendezvous->Personne->Dossierep->join( 'Sanctionrendezvousep58' ),
							$this->Rendezvous->Personne->Dossierep->Sanctionrendezvousep58->join( 'Rendezvous' ),
							$this->Rendezvous->Personne->Dossierep->Sanctionrendezvousep58->Rendezvous->join( 'Typerdv' ),
							$this->Rendezvous->Personne->Dossierep->Sanctionrendezvousep58->Rendezvous->Typerdv->join( 'StatutrdvTyperdv' )
						),
						'conditions' => array(
							'Dossierep.themeep' => 'sanctionsrendezvouseps58',
							'Dossierep.personne_id' => $personne_id,
							'Dossierep.id NOT IN ( '.
							$this->Rendezvous->Personne->Dossierep->Passagecommissionep->sq(
									array(
										'fields' => array(
											'passagescommissionseps.dossierep_id'
										),
										'alias' => 'passagescommissionseps',
										'conditions' => array(
											'passagescommissionseps.etatdossierep' => array( 'traite', 'annule' )
										)
									)
							)
							.' )'
						),
						'order' => array( 'Dossierep.created ASC' )
					)
				);
				$this->set( compact( 'dossierep' ) );

				$dossiercov = $this->Rendezvous->Personne->Dossiercov58->find(
					'first',
					array(
						'fields' => array(
							'StatutrdvTyperdv.motifpassageep',
						),
						'joins' => array(
							$this->Rendezvous->Personne->Dossiercov58->join( 'Propoorientsocialecov58' ),
							$this->Rendezvous->Personne->Dossiercov58->Propoorientsocialecov58->join( 'Rendezvous' ),
							$this->Rendezvous->Personne->Dossiercov58->Propoorientsocialecov58->Rendezvous->join( 'Typerdv' ),
							$this->Rendezvous->Personne->Dossiercov58->Propoorientsocialecov58->Rendezvous->Typerdv->join( 'StatutrdvTyperdv' )
						),
						'conditions' => array(
							'Dossiercov58.themecov58' => 'proposorientssocialescovs58',
							'Dossiercov58.personne_id' => $personne_id,
							'Dossiercov58.id NOT IN ( '.
								$this->Rendezvous->Personne->Dossiercov58->Passagecov58->sq(
									array(
										'fields' => array(
											'passagescovs58.dossiercov58_id'
										),
										'alias' => ' passagescovs58',
										'conditions' => array(
											'passagescovs58.etatdossiercov' => array( 'traite', 'annule' )
										)
									)
								)
							.' )'
						),
						'order' => array( 'Dossiercov58.created ASC' ),
						'contain' => false
					)
				);
				$this->set( compact( 'dossiercov' ) );

				$dossierepLie = $this->Rendezvous->Personne->Dossierep->find(
					'count',
					array(
						'conditions' => array(
							'Dossierep.id IN ( '.
							$this->Rendezvous->Personne->Dossierep->Passagecommissionep->sq(
									array(
										'fields' => array(
											'passagescommissionseps.dossierep_id'
										),
										'alias' => 'passagescommissionseps',
										'conditions' => array(
											'passagescommissionseps.etatdossierep' => array( 'associe', 'decisionep', 'decisioncg', 'traite', 'annule', 'reporte' )
										)
									)
							)
							.' )'
						),
						'joins' => array(
							array(
								'table' => 'sanctionsrendezvouseps58',
								'alias' => 'Sanctionrendezvousep58',
								'type' => 'INNER',
								'conditions' => array(
									'Sanctionrendezvousep58.dossierep_id = Dossierep.id',
									'Sanctionrendezvousep58.rendezvous_id' => $lastrdv_id
								)
							)
						),
						'order' => array( 'Dossierep.created ASC' )
					)
				);

				$dossiercovLie = $this->Rendezvous->Personne->Dossiercov58->find(
					'count',
					array(
						'conditions' => array(
							'OR' => array(
								'Dossiercov58.id IS NULL',
								'Dossiercov58.id IN ( '.
									$this->Rendezvous->Personne->Dossiercov58->Passagecov58->sq(
										array(
											'fields' => array(
												'passagescovs58.dossiercov58_id'
											),
											'alias' => ' passagescovs58',
											'conditions' => array(
												'passagescovs58.etatdossiercov' => array( 'cree', 'associe', 'annule', 'reporte' )
											)
										)
									)
								.' )',
							),
							'Propoorientsocialecov58.rendezvous_id' => $lastrdv_id
						),
						'joins' => array(
							$this->Rendezvous->Personne->Dossiercov58->join( 'Propoorientsocialecov58', array( 'type' => 'LEFT OUTER' ) ),
							$this->Rendezvous->Personne->Dossiercov58->Propoorientsocialecov58->join( 'Rendezvous', array( 'type' => 'LEFT OUTER' ) )
						),
						'order' => array( 'Dossiercov58.created ASC' ),
						'contain' => false
					)
				);

				$this->set( 'dossiercommissionLie', ( $dossierepLie + $dossiercovLie ) );
			}

			$this->set( compact( 'rdvs' ) );
			$this->set( 'personne_id', $personne_id );
		}

		/**
		 *
		 */
		public function view( $rendezvous_id = null ) {
			$this->Rendezvous->forceVirtualFields = true;
			$rendezvous = $this->Rendezvous->find(
				'first',
				array(
					'fields' => array(
						'Rendezvous.personne_id',
						'Personne.nom_complet',
						'Structurereferente.lib_struc',
						'Referent.nom_complet',
						'Referent.fonction',
						'Permanence.libpermanence',
						'Typerdv.libelle',
						'Statutrdv.libelle',
						'Rendezvous.daterdv',
						'Rendezvous.heurerdv',
						'Rendezvous.objetrdv',
						'Rendezvous.commentairerdv'
					),
					'conditions' => array(
						'Rendezvous.id' => $rendezvous_id
					),
					'contain' => array(
						'Typerdv',
						'Referent',
						'Structurereferente',
						'Permanence',
						'Statutrdv',
						'Personne'
					)
				)
			);

			$this->assert( !empty( $rendezvous ), 'invalidParameter' );

			$this->set( 'rendezvous', $rendezvous );
			$this->set( 'personne_id', $rendezvous['Rendezvous']['personne_id'] );
			$this->set( 'urlmenu', '/rendezvous/index/'.$rendezvous['Rendezvous']['personne_id'] );
		}

		/**
		 *
		 */
		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		 *
		 */
		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		 *
		 */
		protected function _add_edit( $id = null ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			// Récupération des id afférents
			if( $this->action == 'add' ) {
				$personne_id = $id;
				$dossier_id = $this->Rendezvous->Personne->dossierId( $personne_id );
			}
			else if( $this->action == 'edit' ) {
				$rdv_id = $id;
				$qd_rdv = array(
					'conditions' => array(
						'Rendezvous.id' => $rdv_id
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$rdv = $this->Rendezvous->find( 'first', $qd_rdv );
				$this->assert( !empty( $rdv ), 'invalidParameter' );

				$personne_id = $rdv['Rendezvous']['personne_id'];
				$dossier_id = $this->Rendezvous->dossierId( $rdv_id );
			}

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			$dossier_id = $this->Rendezvous->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Jetons2->get( $dossier_id );

			$referents = $this->Rendezvous->Referent->listOptions();
			$this->set( 'referents', $referents );


			if( !empty( $this->request->data ) ) {
				$this->Rendezvous->begin();
				if( Configure::read( 'Cg.departement' ) == 58 ) {
					unset( $this->Rendezvous->validate['daterdv'] );
					$this->Rendezvous->validate['daterdv'] = array(
						array(
							'rule' => array( 'notEmpty' ),
							'message' => __( 'Validate::notEmpty', true ),
							'allowEmpty' => false
						),
						array(
							'rule' => 'date',
							'message' => 'Veuillez vérifier le format de la date.'
						)
					);

				}

				$success = $this->Rendezvous->saveAll( $this->request->data, array( 'validate' => 'first', 'atomic' => false ) );

				if( $this->Rendezvous->provoquePassageCommission( $this->request->data ) ) {
					$success = $this->Rendezvous->creePassageCommission( $this->request->data, $this->Session->read( 'Auth.User.id' ) ) && $success;
				}

				if( $success ) {
					$this->Rendezvous->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'rendezvous', 'action' => 'index', $personne_id ) );
				}
				else {
					$this->Rendezvous->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else {
				if( $this->action == 'edit' ) {
					$this->request->data = $rdv;
				}
				else {
					//Récupération de la structure référente liée à l'orientation
					$orientstruct = $this->Rendezvous->Structurereferente->Orientstruct->find(
							'first', array(
						'fields' => array(
							'Orientstruct.id',
							'Orientstruct.personne_id',
							'Orientstruct.structurereferente_id'
						),
						'conditions' => array(
							'Orientstruct.personne_id' => $personne_id,
							'Orientstruct.date_valid IS NOT NULL'
						),
						'contain' => array(
							'Structurereferente',
							'Referent'
						),
						'order' => array( 'Orientstruct.date_valid DESC' )
							)
					);

					if( !empty( $orientstruct ) ) {
						$this->request->data['Rendezvous']['structurereferente_id'] = $orientstruct['Orientstruct']['structurereferente_id'];
					}
				}
			}
//			$this->Rendezvous->commit();

			$struct_id = Set::classicExtract( $this->request->data, "{$this->modelClass}.structurereferente_id" );
			$this->set( 'struct_id', $struct_id );

			$referent_id = Set::classicExtract( $this->request->data, "{$this->modelClass}.referent_id" );
			$referent_id = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $referent_id );
			$this->set( 'referent_id', $referent_id );

			$permanence_id = Set::classicExtract( $this->request->data, "{$this->modelClass}.permanence_id" );
			$permanence_id = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $permanence_id );
			$this->set( 'permanence_id', $permanence_id );

			$typerdv = $this->Rendezvous->Typerdv->find( 'list', array( 'fields' => array( 'id', 'libelle' ) ) );
			$this->set( 'typerdv', $typerdv );

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->set( 'urlmenu', '/rendezvous/index/'.$personne_id );
			$this->render( 'add_edit' );
		}

		/**
		 * Suppression du rendez-vous et du dossier d'EP lié si celui-ci n'est pas
		 * associé à un passage en commission d'EP
		 *
		 * @param integer $id L'id du rendez-vous que l'on souhaite supprimer
		 */
		public function delete( $id ) {
			$rendezvous = $this->Rendezvous->find(
				'first',
				array(
					'fields' => array(
						'Rendezvous.personne_id'
					),
					'conditions' => array(
						'Rendezvous.id' => $id
					),
					'contain' => false
				)
			);

			$dossier_id = $this->Rendezvous->dossierId( $id );
			$this->Jetons2->get( $dossier_id );

			$success = true;

			$this->Rendezvous->begin();

			if( Configure::read( 'Cg.departement' ) == 58 ) {
				$dossierep = $this->Rendezvous->Sanctionrendezvousep58->find(
						'first', array(
					'fields' => array(
						'Sanctionrendezvousep58.id',
						'Sanctionrendezvousep58.dossierep_id'
					),
					'conditions' => array(
						'Sanctionrendezvousep58.rendezvous_id' => $id
					),
					'contain' => false
						)
				);

				if( !empty( $dossierep ) ) {
					$success = $this->Rendezvous->Sanctionrendezvousep58->delete( $dossierep['Sanctionrendezvousep58']['id'] ) && $success;
					$success = $this->Rendezvous->Sanctionrendezvousep58->Dossierep->delete( $dossierep['Sanctionrendezvousep58']['dossierep_id'] ) && $success;
				}
			}

			$success = $this->Rendezvous->delete( $id ) && $success;

			$this->_setFlashResult( 'Delete', $success );

			$this->Jetons2->release( $dossier_id );

			if( $success ) {
				$this->Rendezvous->commit();
			}
			else {
				$this->Rendezvous->rollback();
			}

			$this->redirect( array( 'controller' => 'rendezvous', 'action' => 'index', $rendezvous['Rendezvous']['personne_id'] ) );
		}

		/**
		 * Impression d'un rendez-vous.
		 *
		 * @param integer $rdv_id
		 * @return void
		 */
		public function impression( $rdv_id = null ) { // FIXME: impression
			$pdf = $this->Rendezvous->getDefaultPdf( $rdv_id, $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'rendezvous-%d-%s.pdf', $rdv_id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier de rendez-vous.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

	}
?>