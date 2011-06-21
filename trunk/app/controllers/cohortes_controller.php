<?php
	App::import( 'Sanitize' );
	require_once( APPLIBS.'cmis.php' );

	class CohortesController extends AppController
	{
		public $name = 'Cohortes';

		public $uses = array(
			'Cohorte',
			'Canton',
			'Dossier',
			'Structurereferente',
			'Option',
			'Ressource',
			'Adresse',
			'Typeorient',
			'Structurereferente',
			'Contratinsertion',
			'Detaildroitrsa',
			'Zonegeographique',
			'Adressefoyer',
			'Dsp',
			'Personne',
			'Orientstruct',
			'PersonneReferent',
			'Referent'
		);

		public $helpers = array( 'Csv', 'Paginator', 'Ajax', 'Default', 'Xpaginator' );

		public $components = array( 'Gedooo', 'Prg' => array( 'actions' => 'orientees' ) );

		public $aucunDroit = array( 'progression' );

		public $paginate = array(
			// FIXME
			'limit' => 20,
		);

		/**
		*
		*/

		public function beforeFilter() {
			ini_set('max_execution_time', 0);
			ini_set('memory_limit', '1024M');
			parent::beforeFilter();

			if( in_array( $this->action, array( 'orientees', 'exportcsv' ) ) ) {
				$this->set( 'options', $this->Orientstruct->enums() );
			}
		}

		/**
		*
		*/

		public function __construct() {
			parent::__construct();
			$this->components[] = 'Jetons';
		}


		/**
		*
		*/

		public function nouvelles() {
			$this->_index( 'Non orienté' );
		}

		/**
		*
		*/

		public function orientees() {
			$this->_index( 'Orienté' );
		}

		/**
		*
		*/

		public function enattente() {
			$this->_index( 'En attente' );
		}

		/**
		*
		*/

		protected function _index( $statutOrientation = null ) {
			$this->assert( !empty( $statutOrientation ), 'invalidParameter' );

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );


			// Un des formulaires a été renvoyé
			if( !empty( $this->data ) ) {
				// -----------------------------------------------------------------
				// Formulaire de cohorte
				// -----------------------------------------------------------------
				if( !empty( $this->data['Orientstruct'] ) ) {
					// Sauvegarde de l'utilisateur orientant
					foreach( array_keys( $this->data['Orientstruct'] ) as $key ) {
						if( $this->data['Orientstruct'][$key]['statut_orient'] == 'Orienté' ) {
							$this->data['Orientstruct'][$key]['user_id'] = $this->Session->read( 'Auth.User.id' );
							$this->data['Orientstruct'][$key]['structurereferente_id'] = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $this->data['Orientstruct'][$key]['structurereferente_id'] );
							$this->data['Orientstruct'][$key]['date_valid'] = date( 'Y-m-d' );
						}
						else {
							$this->data['Orientstruct'][$key]['user_id'] = null;
							if( $this->data['Orientstruct'][$key]['statut_orient'] == 'Non orienté' ) {
								$this->data['Orientstruct'][$key]['date_propo'] = date( 'Y-m-d' );
							}
						}
					}

					$valid = $this->Dossier->Foyer->Personne->Orientstruct->saveAll( $this->data['Orientstruct'], array( 'validate' => 'only', 'atomic' => false ) );
					$valid = ( count( $this->Dossier->Foyer->Personne->Orientstruct->validationErrors ) == 0 );
					if( $valid ) {
						$this->Dossier->begin();
						$saved = $this->Dossier->Foyer->Personne->Orientstruct->saveAll( $this->data['Orientstruct'], array( 'validate' => 'first', 'atomic' => false ) );

						if( $saved ) {
							$dossiersIds = Set::extract( $this->data, '/Orientstruct/dossier_id' );
							$this->Jetons->releaseList( $dossiersIds ); // FIXME -> bien passé / mal passé

							$this->Dossier->commit();
							$this->data['Orientstruct'] = array();
						}
						else {
							$this->Dossier->rollback();
							$this->Session->setFlash( 'Erreur lors de l\'enregistrement.', 'flash/error' );
						}
					}
				}

				// -----------------------------------------------------------------
				// Filtre
				// -----------------------------------------------------------------
				if( isset( $this->data['Filtre'] ) ) {
					$this->Cohorte->begin();

					$queryData = $this->Cohorte->recherche(
						$statutOrientation,
						$mesCodesInsee,
						$this->Session->read( 'Auth.User.filtre_zone_geo' ),
						$this->data,
						$this->Jetons->ids()
					);

					if( $statutOrientation == 'Orienté' ) {
						$queryData['limit'] = 10;
// debug( $queryData );
						$this->paginate = array( 'Personne' => $queryData );
						$cohorte = $this->paginate( $this->Personne );

						$this->set( compact( 'cohorte' ) );
					}
					else { // FIXME: jetons
						$queryData['limit'] = 10;

						$this->paginate = array( 'Personne' => $queryData );
						$cohorte = $this->paginate( $this->Personne );

						$this->set( compact( 'cohorte' ) );
					}

					// Acquisition des jetons si on a un formulaire de cohorte -> FIXME begin/commit/rollback
					if( ( ( $statutOrientation == 'En attente' ) || ( $statutOrientation == 'Non orienté' ) ) && !empty( $cohorte ) ) {
						$dossiersIds = Set::extract( $cohorte, '/Dossier/id' );
						$this->Jetons->getList( $dossiersIds );
					}

					$this->Cohorte->commit();
				}

				// -----------------------------------------------------------------
			}

			// -----------------------------------------------------------------

			$typesOrient = $this->Typeorient->find(
				'list',
				array(
					'fields' => array(
						'Typeorient.id',
						'Typeorient.lib_type_orient'
					),
					'order' => 'Typeorient.lib_type_orient ASC'
				)
			);
			$this->set( 'typesOrient', $typesOrient );
			$this->set( 'structuresReferentes', $this->Structurereferente->list1Options() );

			// -----------------------------------------------------------------

			if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
				$this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
			}
			else {
				$this->set( 'mesCodesInsee', $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee() );
			}

			$this->set( 'oridemrsa', $this->Option->oridemrsa() );
			$this->set( 'typeserins', $this->Option->typeserins() );
			$this->set( 'printed', $this->Option->printed() );
			$this->set( 'structuresAutomatiques', $this->Cohorte->structuresAutomatiques() );
			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', $this->Canton->selectList() );
			}

			$this->set(
				'modeles',
				$this->Typeorient->find(
					'list',
					array(
						'fields' => array( 'lib_type_orient' ),
						'conditions' => array( 'Typeorient.parentid IS NULL' )
					)
				)
			);

			// -----------------------------------------------------------------

			switch( $statutOrientation ) {
				case 'En attente':
					$this->set( 'pageTitle', 'Nouvelles demandes à orienter' );
					$this->render( $this->action, null, 'formulaire' );
					break;
				case 'Non orienté':
					$this->set( 'pageTitle', 'Demandes non orientées' );
					$this->render( $this->action, null, 'formulaire' );
					break;
				case 'Orienté': // FIXME: pas besoin de locker
					$this->set( 'pageTitle', 'Demandes orientées' );
					$this->render( $this->action, null, 'visualisation' );
					break;
			}
		}

		/**
		* Export des données en Xls
		*/

		public function exportcsv(){
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$_limit = 10;
			$params = $this->Cohorte->search( 'Orienté', $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );

			unset( $params['limit'] );
			$cohortes = $this->Dossier->find( 'all', $params );

			$this->layout = ''; // FIXME ?
			$this->set( compact( 'cohortes' ) );
		}

		/**
		*
		*/

		public function cohortegedooo( $personne_id = null ) {
			$this->Dossier->begin();

			$AuthZonegeographique = $this->Session->read( 'Auth.Zonegeographique' );
			if( !empty( $AuthZonegeographique ) ) {
				$AuthZonegeographique = array_values( $AuthZonegeographique );
			}
			else {
				$AuthZonegeographique = array();
			}

			$queryData = $this->Cohorte->recherche( 'Orienté', $AuthZonegeographique, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ), $this->Jetons->ids() );

			if( $limit = Configure::read( 'nb_limit_print' ) ) {
				$queryData['limit'] = $limit;
			}

			$queryData['fields'] = array(
				'Orientstruct.id',
				'Pdf.document',
			);

			$queryData['joins'][] = array(
				'table'      => 'pdfs',
				'alias'      => 'Pdf',
				'type'       => 'INNER',
				'foreignKey' => false,
				'conditions' => array(
					'Pdf.fk_value = Orientstruct.id',
					'Pdf.modele' => 'Orientstruct',
				)
			);

			if( !empty( $this->params['named']['sort'] ) && !empty( $this->params['named']['direction'] ) ) {
				$queryData['order'] = array( "{$this->params['named']['sort']} {$this->params['named']['direction']}" );
			}

			$queryData['fields'][] = 'Pdf.cmspath';

			$results = $this->Personne->find( 'all', $queryData );

			// Si le contenu du PDF n'est pas dans la table pdfs, aller le chercher sur le serveur CMS
			$nErrors = 0;
			foreach( $results as $i => $result ) {
				if( empty( $result['Pdf']['document'] ) && !empty( $result['Pdf']['cmspath'] ) ) {
					$pdf = Cmis::read( $result['Pdf']['cmspath'], true );
					if( !empty( $pdf['content'] ) ) {
						$results[$i]['Pdf']['document'] = $pdf['content'];
					}
				}
				// Gestion des erreurs: si on n'a toujours pas le document
				if( empty( $results[$i]['Pdf']['document'] ) ) {
					$nErrors++;
					unset( $results[$i] );
				}
			}

			if( $nErrors > 0 ) {
				$this->Session->setFlash( "Erreur lors de l'impression en cohorte: {$nErrors} documents n'ont pas pu être imprimés. Abandon de l'impression de la cohorte. Demandez à votre administrateur d'exécuter cake/console/cake generationpdfs orientsstructs -username <username> (où <username> est l'identifiant de l'utilisateur qui sera utilisé pour la récupération d'informations lors de l'impression)", 'flash/error' );
				$this->redirect( $this->referer() );
			}

			$content = $this->Gedooo->concatPdfs( Set::extract( $results, '/Pdf/document' ), 'orientsstructs' );

			$success = ( $content !== false ) && $this->Orientstruct->updateAll(
				array( 'Orientstruct.date_impression' => date( "'Y-m-d'" ) ),
				array(
					'"Orientstruct"."id"' => Set::extract( $results, '/Orientstruct/id' ),
					'"Orientstruct"."date_impression" IS NULL'
				)
			);

			if( $content !== false ) { // date_impression
				$this->Dossier->commit();
				$this->Gedooo->sendPdfContentToClient( $content, sprintf( "cohorte-orientations-%s.pdf", date( "Ymd-H\hi" ) ) );
				die();
			}
			else {
				$this->Dossier->rollback();
				$this->Session->setFlash( 'Erreur lors de l\'impression en cohorte.', 'flash/error' );
				$this->redirect( $this->referer() );
			}
		}
	}
?>
