<?php
	class Relancesnonrespectssanctionseps93Controller extends AppController
	{
		public $uses = array( 'Relancenonrespectsanctionep93', 'Nonrespectsanctionep93', 'Orientstruct', 'Contratinsertion', 'Dossierep', 'Dossier', 'Pdf' );

		public $components = array( 'Prg' => array( 'actions' => array( 'cohorte' => array( 'filter' => 'Search' ), 'impressions' ) ), 'Gedooo.Gedooo' );
		public $helpers = array( 'Default2', 'Csv' );

		/**
		*
		*/

		protected function _setOptions() {
			/// Mise en cache (session) de la liste des codes Insee pour les selects
			/// TODO: Une fonction ?
			/// TODO: Voir où l'utiliser ailleurs
			if( !$this->Session->check( 'Cache.mesCodesInsee' ) ) {
				if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
					$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
					$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );
					$listeCodesInseeLocalites = $this->Dossier->Foyer->Personne->Cui->Structurereferente->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) );
				}
				else {
					$listeCodesInseeLocalites = $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee();
				}
				$this->Session->write( 'Cache.mesCodesInsee', $listeCodesInseeLocalites );
			}
			else {
				$listeCodesInseeLocalites = $this->Session->read( 'Cache.mesCodesInsee' );
			}

			$options = array(
				'Adresse' => array( 'numcomptt' => $listeCodesInseeLocalites ),
				'Serviceinstructeur' => array( 'id' => $this->Orientstruct->Serviceinstructeur->find( 'list' ) )
			);
			$options = Set::merge(
				$options,
				$this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->enums(),
				$this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->Dossierep->enums(),
				$this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->Dossierep->Passagecommissionep->enums()
			);

			$this->set( compact( 'options' ) );
		}

		/**
		*
		*/

		public function index( $personne_id = null ) {
			$this->assert( is_numeric( $personne_id ), 'invalidParameter' );

			$erreurs = $this->Relancenonrespectsanctionep93->erreursPossibiliteAjout( $personne_id );

			$conditions = array( 'OR' => array(), 'Nonrespectsanctionep93.origine' => array( 'orientstruct', 'contratinsertion' ) );

			$orientsstructs = $this->Orientstruct->find(
				'list',
				array(
					'conditions' => array(
						'Orientstruct.personne_id' => $personne_id
					)
				)
			);
			if( !empty( $orientsstructs ) ) {
				$conditions['OR']['Nonrespectsanctionep93.orientstruct_id'] = $orientsstructs;
			}

			$contratsinsertion = $this->Contratinsertion->find(
				'list',
				array(
					'conditions' => array(
						'Contratinsertion.personne_id' => $personne_id
					)
				)
			);
			if( !empty( $contratsinsertion ) ) {
				$conditions['OR']['Nonrespectsanctionep93.contratinsertion_id'] = $contratsinsertion;
			}

			$relances = array();
			if( !empty( $conditions['OR'] ) ) {
				$personne = $this->Nonrespectsanctionep93->Orientstruct->Personne->find(
					'first',
					array(
						'conditions' => array(
							'Personne.id' => $personne_id
						),
						'contain' => array(
							'Foyer' => array(
								'Dossier',
								'Adressefoyer' => array(
									'conditions' => array(
										'Adressefoyer.rgadr' => '01'
									),
									'Adresse'
								)
							)
						),
					)
				);
				$relances = $this->Nonrespectsanctionep93->find(
					'all',
					array(
						'fields' => array(
							'Orientstruct.id',
							'Orientstruct.date_valid',
							'Contratinsertion.id',
							'Contratinsertion.df_ci',
							'Relancenonrespectsanctionep93.id',
							'Relancenonrespectsanctionep93.numrelance',
							'Relancenonrespectsanctionep93.daterelance',
							'Pdf.id',
						),
						'conditions' => $conditions,
						'joins' => array(
							array(
								'table'      => 'relancesnonrespectssanctionseps93',
								'alias'      => 'Relancenonrespectsanctionep93',
								'type'       => 'INNER',
								'foreignKey' => false,
								'conditions' => array(
									'Relancenonrespectsanctionep93.nonrespectsanctionep93_id = Nonrespectsanctionep93.id'
								)
							),
							array(
								'table'      => 'orientsstructs',
								'alias'      => 'Orientstruct',
								'type'       => 'LEFT OUTER',
								'foreignKey' => false,
								'conditions' => array(
									'Orientstruct.id = Nonrespectsanctionep93.orientstruct_id'
								)
							),
							array(
								'table'      => 'contratsinsertion',
								'alias'      => 'Contratinsertion',
								'type'       => 'LEFT OUTER',
								'foreignKey' => false,
								'conditions' => array(
									'Contratinsertion.id = Nonrespectsanctionep93.contratinsertion_id'
								)
							),
							array(
								'table'      => 'pdfs',
								'alias'      => 'Pdf',
								'type'       => 'LEFT OUTER',
								'foreignKey' => false,
								'conditions' => array(
									'Relancenonrespectsanctionep93.id = Pdf.fk_value',
									'Pdf.modele' => 'Relancenonrespectsanctionep93',
								)
							),
						),
						'order' => array( 'Relancenonrespectsanctionep93.daterelance DESC', 'Relancenonrespectsanctionep93.numrelance DESC' )
					)
				);
			}

			$this->set( compact( 'relances', 'erreurs', 'personne' ) );
			$this->set( 'personne_id', $personne_id );
		}

		/**
		*
		*/

		public function cohorte() {
			if( !empty( $this->data ) ) {
				$search = $this->data['Search'];

				$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
				$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

				/// Enregistrement de la cohorte de relances
				if( isset( $this->data['Relancenonrespectsanctionep93'] ) ) {
					$data = $this->data['Relancenonrespectsanctionep93'];

					// On filtre les relances en attente
					$newData = array();
					foreach( $data as $i => $relance ) {
						if( $relance['arelancer'] == 'R' ) {
							$newData[$i] = $relance;
						}
					}

					if( !empty( $newData ) ) {
						$this->Nonrespectsanctionep93->begin();

						// Relances non respect orientation
						$success = $this->Relancenonrespectsanctionep93->saveCohorte( $newData, $search );

						$this->_setFlashResult( 'Save', $success );
						if( $success ) {
							unset( $this->data['Relancenonrespectsanctionep93'], $this->data['sessionKey'] );
							$this->Nonrespectsanctionep93->commit();
							$url = Router::url( Set::merge( array( 'action' => $this->action ), Set::flatten( $this->data ) ), true );
							$this->redirect( $url );
						}
						else {
							$this->Nonrespectsanctionep93->rollback();
						}
					}
				}

				/// Moteur de recherche
				$search = Set::flatten( $search );
				$search = Set::filter( $search );

				if( $this->data['Search']['Relance']['contrat'] == 0 ) {
					$this->paginate['Orientstruct'] = $this->Relancenonrespectsanctionep93->search(
						$mesCodesInsee,
						$this->Session->read( 'Auth.User.filtre_zone_geo' ),
						$search
					);

					$results = $this->paginate( $this->Nonrespectsanctionep93->Orientstruct );

					if( !empty( $results ) ) {
						foreach( $results as $i => $result ) {
							// Calcul de la date de relance minimale
							if( $search['Relance.numrelance'] == 1 ) {
								$results[$i]['Nonrespectsanctionep93']['datemin'] = date(
									'Y-m-d',
									strtotime(
										'+'.( Configure::read( 'Nonrespectsanctionep93.relanceOrientstructCer1' ) + 1 ).' days',
										strtotime( $result['Orientstruct']['date_impression'] )
									)
								);
							}
							else if( $search['Relance.numrelance'] > 1 ) {
								$results[$i]['Nonrespectsanctionep93']['datemin'] = date(
									'Y-m-d',
									strtotime(
										'+'.( Configure::read( "Nonrespectsanctionep93.relanceOrientstructCer{$search['Relance.numrelance']}" ) + 1 ).' days',
										strtotime( $result['Relancenonrespectsanctionep93']['daterelance'] )
									)
								);
							}

							$results[$i]['Orientstruct']['nbjours'] = round(
								( mktime() - strtotime( $result['Orientstruct']['date_impression'] ) ) / ( 60 * 60 * 24 )
							);
						}
					}
				}
				else if( $this->data['Search']['Relance']['contrat'] == 1 ) {
					$this->paginate['Contratinsertion'] = $this->Relancenonrespectsanctionep93->search(
						$mesCodesInsee,
						$this->Session->read( 'Auth.User.filtre_zone_geo' ),
						$search
					);

					$results = $this->paginate( $this->Nonrespectsanctionep93->Contratinsertion );

					if( !empty( $results ) ) {
						foreach( $results as $i => $result ) {
							// Calcul de la date de relance minimale
							if( $search['Relance.numrelance'] == 1 ) {
								$results[$i]['Nonrespectsanctionep93']['datemin'] = date(
									'Y-m-d',
									strtotime(
										'+'.( Configure::read( 'Nonrespectsanctionep93.relanceCerCer1' ) + 1 ).' days',
										strtotime( $result['Contratinsertion']['df_ci'] )
									)
								);
							}
							else if( $search['Relance.numrelance'] > 1 ) {
								$results[$i]['Nonrespectsanctionep93']['datemin'] = date(
									'Y-m-d',
									strtotime(
										'+'.( Configure::read( "Nonrespectsanctionep93.relanceCerCer{$search['Relance.numrelance']}" ) + 1 ).' days',
										strtotime( $result['Relancenonrespectsanctionep93']['daterelance'] )
									)
								);
							}

							$results[$i]['Contratinsertion']['nbjours'] = round(
								( mktime() - strtotime( $result['Contratinsertion']['df_ci'] ) ) / ( 60 * 60 * 24 )
							);
						}
					}
				};

				$this->set( compact( 'results' ) );

				if( $this->Relancenonrespectsanctionep93->checkCompareError( Xset::bump( $search ) ) == true ) {
					$this->Session->setFlash( 'Vos critères de recherche entrent en contradiction avec les critères de base', 'flash/error' );
				}
			}

			$this->_setOptions();
		}

		/**
		*
		*/

		public function add( $personne_id ) {
			$erreurs = $this->Relancenonrespectsanctionep93->erreursPossibiliteAjout( $personne_id );
			if( !empty( $erreurs ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}
			else {
				$orientstruct = $this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->Orientstruct->find(
					'first',
					array(
						'conditions' => array(
							'Orientstruct.personne_id' => $personne_id,
							'Orientstruct.statut_orient' => 'Orienté',
							'Orientstruct.date_valid IS NOT NULL',
							'Orientstruct.date_impression IS NOT NULL',
						),
						'order' => array( 'Orientstruct.date_impression DESC' ),
						'contain' => false
					)
				);

				$contratinsertion = $this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->Contratinsertion->find(
					'first',
					array(
						'conditions' => array(
							'Contratinsertion.personne_id' => $personne_id,
							'Contratinsertion.decision_ci' => 'V',
							'Contratinsertion.df_ci IS NOT NULL',
							'Contratinsertion.datevalidation_ci IS NOT NULL',

						),
						'order' => array( 'Contratinsertion.df_ci DESC' ),
						'contain' => false
					)
				);

				if( ( empty( $orientstruct ) && !empty( $contratinsertion ) ) || ( strtotime( $orientstruct['Orientstruct']['date_impression'] ) < strtotime( $contratinsertion['Contratinsertion']['datevalidation_ci'] ) ) ) {
					$orientstruct_id = null;
					$contratinsertion_id = $contratinsertion['Contratinsertion']['id'];
					$origine = 'contratinsertion';
				}
				else {
					$orientstruct_id = $orientstruct['Orientstruct']['id'];
					$contratinsertion_id = null;
					$origine = 'orientstruct';
				}

				// Calcul du rang de la relance
				$relances_pcd = $this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->find(
					'all',
					array(
						'fields' => array(
							'Nonrespectsanctionep93.id',
							'Relancenonrespectsanctionep93.daterelance',
						),
						'conditions' => array(
							'Nonrespectsanctionep93.contratinsertion_id' => $contratinsertion_id,
							'Nonrespectsanctionep93.orientstruct_id' => $orientstruct_id,
							'Nonrespectsanctionep93.origine' => $origine,
							'Nonrespectsanctionep93.dossierep_id IS NULL',
							'Nonrespectsanctionep93.active' => 1,
						),
						'joins' => array(
							array(
								'table'      => 'relancesnonrespectssanctionseps93',
								'alias'      => 'Relancenonrespectsanctionep93',
								'type'       => 'INNER',
								'foreignKey' => false,
								'conditions' => array(
									'Relancenonrespectsanctionep93.nonrespectsanctionep93_id = Nonrespectsanctionep93.id'
								)
							),
						),
						'order' => array( 'Relancenonrespectsanctionep93.daterelance DESC' )
					)
				);
				$numrelance_pcd = count( $relances_pcd );
				$numrelance = ( $numrelance_pcd + 1 );

				$data = Set::merge( ( $origine == 'contratinsertion' ? $contratinsertion : $orientstruct ), @$relances_pcd[0] );
				$daterelance_min = $this->Relancenonrespectsanctionep93->dateRelanceMinimale( $origine, $numrelance, $data );
				$this->set( 'daterelance_min', $daterelance_min );

				// Calcul du rang de passage
				$rgpassage_pcd = $this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->find(
					'count',
					array(
						'conditions' => array(
							'Nonrespectsanctionep93.contratinsertion_id' => $contratinsertion_id,
							'Nonrespectsanctionep93.orientstruct_id' => $orientstruct_id,
							'Nonrespectsanctionep93.origine' => $origine,
							'Nonrespectsanctionep93.dossierep_id IS NOT NULL',
							'Nonrespectsanctionep93.active' => 0,
						)
					)
				);

				$nonrespectsanctionep93 = $this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->find(
					'first',
					array(
						'conditions' => array(
							'Nonrespectsanctionep93.contratinsertion_id' => $contratinsertion_id,
							'Nonrespectsanctionep93.orientstruct_id' => $orientstruct_id,
							'Nonrespectsanctionep93.origine' => $origine,
							'Nonrespectsanctionep93.dossierep_id IS NULL',
							'Nonrespectsanctionep93.active' => 1,
						)
					)
				);

				$this->set( compact( 'origine', 'numrelance' ) );

				if( !empty( $this->data ) ) {
					if( empty( $this->data['Nonrespectsanctionep93']['id'] ) ) {
						unset( $this->data['Nonrespectsanctionep93']['id'] );
					}
					if( empty( $this->data['Relancenonrespectsanctionep93']['id'] ) ) {
						unset( $this->data['Relancenonrespectsanctionep93']['id'] );
					}
					if( !empty( $nonrespectsanctionep93 ) ) {
						$this->data['Nonrespectsanctionep93']['id'] = $nonrespectsanctionep93['Nonrespectsanctionep93']['id'];
					}

					$this->data['Nonrespectsanctionep93']['orientstruct_id'] = $orientstruct_id;
					$this->data['Nonrespectsanctionep93']['contratinsertion_id'] = $contratinsertion_id;
					$this->data['Nonrespectsanctionep93']['origine'] = $origine;
					$this->data['Nonrespectsanctionep93']['rgpassage'] = ( $rgpassage_pcd + 1 );

					$this->data['Relancenonrespectsanctionep93']['numrelance'] = $numrelance;

					if( $origine == 'contratinsertion' ) {
						$timediff = Configure::read( "Nonrespectsanctionep93.relanceOrientstructCer{$numrelance}" );
					}
					else {
						$timediff = Configure::read( "Nonrespectsanctionep93.relanceCerCer{$numrelance}" );
					}

					$this->Relancenonrespectsanctionep93->begin();

					$success = true;
					if( ( $origine == 'orientstruct' && $this->data['Relancenonrespectsanctionep93']['numrelance'] == 3 ) || ( $origine == 'contratinsertion' && $this->data['Relancenonrespectsanctionep93']['numrelance'] == 2 ) ) {
						$dossierep = array(
							'Dossierep' => array(
								'personne_id' => $personne_id,
								'themeep' => 'nonrespectssanctionseps93',
							),
						);

						$this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->Dossierep->create( $dossierep );
						$success = $this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->Dossierep->save();

						$this->data['Nonrespectsanctionep93']['dossierep_id'] = $this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->Dossierep->id;
					}

					$this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->create( $this->data );
					$success = $this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->save() && $success;

					$this->data['Relancenonrespectsanctionep93']['nonrespectsanctionep93_id'] = $this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->id;

					$this->Relancenonrespectsanctionep93->create( $this->data );
					$success = $this->Relancenonrespectsanctionep93->save() && $success;

					$this->_setFlashResult( 'Save', $success );
					if( $success ) {
						$this->Relancenonrespectsanctionep93->commit();
						$this->redirect( array( 'action' => 'index', $personne_id ) );
					}
					else {
						$this->Relancenonrespectsanctionep93->rollback();
					}
				}
			}

			$this->set( 'personne_id', $personne_id );
		}

		/**
		*
		*/

		public function impressions() {
			if( !empty( $this->data ) ) {
				$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
				$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

				$queryData = $this->Relancenonrespectsanctionep93->qdSearchRelances(
					$mesCodesInsee,
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$this->data
				);

				$queryData['limit'] = 10;

				$this->Relancenonrespectsanctionep93->forceVirtualFields = true;

				$this->paginate = $queryData;
				$relances = $this->paginate( $this->Relancenonrespectsanctionep93 );

				$this->set( compact( 'relances' ) );
			}

			$this->_setOptions();
		}

		/**
		*
		*/

		public function exportcsv() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$queryData = $this->Relancenonrespectsanctionep93->qdSearchRelances(
				$mesCodesInsee,
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				Xset::bump( $this->params['named'], '__' )
			);

			$this->Relancenonrespectsanctionep93->forceVirtualFields = true;

			$relances = $this->Relancenonrespectsanctionep93->find( 'all', $queryData );

			$this->layout = '';
			$this->set( compact( 'relances' ) );
			$this->_setOptions();
		}

		/**
		*
		*/

		public function impression( $id ) {
			$this->assert( is_numeric( $id ), 'invalidParameter' );

			$this->Relancenonrespectsanctionep93->begin();

			$pdf = $this->Relancenonrespectsanctionep93->getStoredPdf( $id, 'dateimpression' );

			if( empty( $pdf ) ) {
				$this->Relancenonrespectsanctionep93->rollback();
				$this->cakeError( 'error404' );
			}
			else if( !empty( $pdf['Pdf']['document'] ) ) {
				$this->Relancenonrespectsanctionep93->commit();
				$this->layout = '';
				$this->Gedooo->sendPdfContentToClient( $pdf['Pdf']['document'], sprintf( "relance-%s.pdf", date( "Ymd-H\hi" ) ) );
			}
			else {
				$this->Relancenonrespectsanctionep93->rollback();
				$this->cakeError( 'error500' );
			}

		}

		/**
		*
		*/

		public function impression_cohorte() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$queryData = $this->Relancenonrespectsanctionep93->qdSearchRelances(
				$mesCodesInsee,
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				Xset::bump( $this->params['named'], '__' )
			);

			$queryData['fields'] = array(
				'Pdf.document',
				'Pdf.cmspath',
				'Relancenonrespectsanctionep93.id',
				'Relancenonrespectsanctionep93.dateimpression',
			);

			$this->Relancenonrespectsanctionep93->begin();

			$nErrors = 0;
			$contents = $this->Relancenonrespectsanctionep93->find( 'all', $queryData );
			foreach( $contents as $i => $content ) {
				if( empty( $content['Pdf']['document'] ) && !empty( $content['Pdf']['cmspath'] ) ) {
					$cmisPdf = Cmis::read( $content['Pdf']['cmspath'], true );
					if( !empty( $cmisPdf['content'] ) ) {
						$contents[$i]['Pdf']['document'] = $cmisPdf['content'];
					}
				}
				// Gestion des erreurs: si on n'a toujours pas le document
				if( empty( $contents[$i]['Pdf']['document'] ) ) {
					$nErrors++;
					unset( $results[$i] );
				}
			}

			if( $nErrors > 0 ) {
				$this->Session->setFlash( "Erreur lors de l'impression en cohorte: {$nErrors} documents n'ont pas pu être imprimés. Abandon de l'impression de la cohorte. Demandez à votre administrateur d'exécuter cake/console/cake generationpdfs relancenonrespectsanctionep93", 'flash/error' );
				$this->redirect( $this->referer() );
			}

			$ids = Set::extract( '/Relancenonrespectsanctionep93/id', $contents );
			$pdfs = Set::extract( '/Pdf/document', $contents );

			if( empty( $content['Relancenonrespectsanctionep93']['dateimpression'] ) ) {
				$this->Relancenonrespectsanctionep93->updateAll(
					array( 'Relancenonrespectsanctionep93.dateimpression' => date( "'Y-m-d'" ) ),
					array( '"Relancenonrespectsanctionep93"."id"' => $ids, '"Relancenonrespectsanctionep93"."dateimpression" IS NOT NULL' )
				);
			}

			$pdfs = $this->Gedooo->concatPdfs( $pdfs, 'Relancenonrespectsanctionep93' );

			if( !empty( $pdfs ) ) {
				$this->Relancenonrespectsanctionep93->commit();
				$this->layout = '';
				$this->Gedooo->sendPdfContentToClient( $pdfs, sprintf( "cohorterelances-%s.pdf", date( "Ymd-H\hi" ) ) );
			}
			else {
				$this->Relancenonrespectsanctionep93->rollback();
				$this->Session->setFlash( 'Erreur lors de l\'impression en cohorte.', 'flash/error' );
				$this->redirect( $this->referer() );
			}
		}
	}
?>