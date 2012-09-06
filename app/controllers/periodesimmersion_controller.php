<?php
	App::import( 'Helper', 'Locale' );
	class PeriodesimmersionController extends AppController
	{

		public $name = 'Periodesimmersion';
		public $uses = array( 'Periodeimmersion', 'Cui', 'Option', 'Referent', 'Personne', 'Dossier', 'Adressefoyer', 'Structurereferente' );
		public $helpers = array( 'Default', 'Default2', 'Locale', 'Csv', 'Ajax', 'Xform' );
		public $components = array( 'RequestHandler', 'Gedooo.Gedooo' );
		public $aucunDroit = array( 'gedooo' );
		public $commeDroit = array(
			'view' => 'Periodesimmersion:index',
			'add' => 'Periodesimmersion:edit'
		);

		/**
		 *
		 */
		protected function _setOptions() {
			$options = array( );
			$options = $this->Periodeimmersion->allEnumLists();
			$optionscui = $this->Cui->allEnumLists();
			$options = Set::merge( $optionscui, $options );
			$typevoie = $this->Option->typevoie();
			$this->set( 'rolepers', $this->Option->rolepers() );
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'nationalite', $this->Option->nationalite() );


			$typevoie = $this->Option->typevoie();
			$options = Set::insert( $options, 'typevoie', $typevoie );

			$this->set( compact( 'options', 'dept' ) );
		}

		/**
		 *
		 */
		public function index( $cui_id = null ) {
			$nbrCuis = $this->Periodeimmersion->Cui->find( 'count', array( 'conditions' => array( 'Cui.id' => $cui_id ), 'recursive' => -1 ) );
			$this->assert( ( $nbrCuis == 1 ), 'invalidParameter' );

			$qd_cui = array(
				'conditions' => array(
					'Cui.id' => $cui_id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$cui = $this->Cui->find( 'first', $qd_cui );

			$personne_id = Set::classicExtract( $cui, 'Cui.personne_id' );

			$periodesimmersion = $this->Periodeimmersion->find(
					'all', array(
				'conditions' => array(
					'Periodeimmersion.cui_id' => $cui_id
				),
				'recursive' => -1
					)
			);

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->set( 'cui_id', $cui_id );
			$this->set( compact( 'cuis', 'periodesimmersion' ) );
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );

			// Retour à la liste des CUI en cas de retour
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'cuis', 'action' => 'index', $personne_id ) );
			}
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
			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				if( $this->action == 'edit' ) {
					$id = $this->Periodeimmersion->field( 'cui_id', array( 'id' => $id ) );
				}
				$this->redirect( array( 'action' => 'index', $id ) );
			}

			if( $this->action == 'add' ) {
				$cui_id = $id;
				$qd_cui = array(
					'conditions' => array(
						'Cui.id' => $cui_id
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$cui = $this->Cui->find( 'first', $qd_cui );

				$personne_id = Set::classicExtract( $cui, 'Cui.personne_id' );
			}
			else if( $this->action == 'edit' ) {
				$periodeimmersion_id = $id;
				$qd_periodeimmersion = array(
					'conditions' => array(
						'Periodeimmersion.id' => $periodeimmersion_id
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$periodeimmersion = $this->Periodeimmersion->find( 'first', $qd_periodeimmersion );

				$this->assert( !empty( $periodeimmersion ), 'invalidParameter' );

				$cui_id = Set::classicExtract( $periodeimmersion, 'Periodeimmersion.cui_id' );
				$qd_cui = array(
					'conditions' => array(
						'Cui.id' => $cui_id
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$cui = $this->Cui->find( 'first', $qd_cui );

				$personne_id = Set::classicExtract( $cui, 'Cui.personne_id' );
			}

			/// Peut-on prendre le jeton ?
			$this->Periodeimmersion->begin();
			$dossier_id = $this->Periodeimmersion->Cui->Personne->dossierId( $personne_id );
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Periodeimmersion->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );
			$this->set( 'dossier_id', $dossier_id );

			///On ajout l'ID de l'utilisateur connecté afind e récupérer son service instructeur
			$personne = $this->{$this->modelClass}->Cui->Personne->detailsApre( $personne_id, $this->Session->read( 'Auth.User.id' ) );
			$this->set( 'personne', $personne );
			$this->set( 'cui', $cui );
			$this->set( 'cui_id', $cui_id );

			$this->set( 'referents', $this->Referent->find( 'list' ) );
			$this->set( 'structs', $this->Structurereferente->listOptions() );

			if( !empty( $this->data ) ) {

				$valid = $this->Periodeimmersion->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) );

				if( $valid ) {
					$saved = $this->Periodeimmersion->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) );

					if( $saved ) {
						$this->Jetons->release( $dossier_id );
						$this->Periodeimmersion->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'periodesimmersion', 'action' => 'index', $cui_id ) );
					}
					else {
						$this->Periodeimmersion->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
			}
			else {
				if( $this->action == 'edit' ) {
					$this->data = $periodeimmersion;
				}
			}

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );
			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		 *
		 */
		public function gedooo( $id ) {
			$qual = $this->Option->qual();
			$typevoie = $this->Option->typevoie();
			$options = $this->{$this->modelClass}->allEnumLists();

			$periodeimmersion = $this->{$this->modelClass}->find(
				'first',
				array(
					'conditions' => array(
						"{$this->modelClass}.id" => $id
					),
					'contain' => array(
						'Cui'
					)
				)
			);

			$personne_id = Set::classicExtract( $periodeimmersion, 'Cui.personne_id' );
			$qd_personne = array(
				'conditions' => array(
					'Personne.id' => $personne_id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$personne = $this->Personne->find( 'first', $qd_personne );

			$periodeimmersion['Personne'] = $personne['Personne'];

			$this->Adressefoyer->bindModel(
					array(
						'belongsTo' => array(
							'Adresse' => array(
								'className' => 'Adresse',
								'foreignKey' => 'adresse_id'
							)
						)
					)
			);

			$adresse = $this->Adressefoyer->find(
				'first',
				array(
					'conditions' => array(
						'Adressefoyer.foyer_id' => Set::classicExtract( $periodeimmersion, 'Personne.foyer_id' ),
						'Adressefoyer.rgadr' => '01',
					)
				)
			);
			$periodeimmersion['Adresse'] = $adresse['Adresse'];

			$periodeimmersion_id = Set::classicExtract( $periodeimmersion, 'Actioncandidat.id' );

			///Traduction pour les données de la Personne/Contact/Partenaire/Référent
			$LocaleHelper = new LocaleHelper();
			//Données Periode immersion
			$periodeimmersion['Periodeimmersion']['typevoieentaccueil'] = Set::enum( Set::classicExtract( $periodeimmersion, 'Periodeimmersion.typevoieentaccueil' ), $typevoie );
			$periodeimmersion['Periodeimmersion']['datedebperiode'] = $LocaleHelper->date( 'Date::short', Set::classicExtract( $periodeimmersion, 'Periodeimmersion.datedebperiode' ) );
			$periodeimmersion['Periodeimmersion']['datefinperiode'] = $LocaleHelper->date( 'Date::short', Set::classicExtract( $periodeimmersion, 'Periodeimmersion.datefinperiode' ) );
			$periodeimmersion['Periodeimmersion']['datesignatureimmersion'] = $LocaleHelper->date( 'Date::short', Set::classicExtract( $periodeimmersion, 'Periodeimmersion.datesignatureimmersion' ) );
			$periodeimmersion['Periodeimmersion']['objectifimmersion'] = Set::enum( Set::classicExtract( $periodeimmersion, 'Periodeimmersion.objectifimmersion' ), $options['objectifimmersion'] );
			//Données Cui
			$periodeimmersion['Cui']['datedebprisecharge'] = $LocaleHelper->date( 'Date::short', Set::classicExtract( $periodeimmersion, 'Cui.datedebprisecharge' ) );
			$periodeimmersion['Cui']['datefinprisecharge'] = $LocaleHelper->date( 'Date::short', Set::classicExtract( $periodeimmersion, 'Cui.datefinprisecharge' ) );
			$periodeimmersion['Cui']['datecontrat'] = $LocaleHelper->date( 'Date::short', Set::classicExtract( $periodeimmersion, 'Cui.datecontrat' ) );
			//Données Personne
			$periodeimmersion['Personne']['qual'] = Set::enum( Set::classicExtract( $periodeimmersion, 'Personne.qual' ), $qual );
			$periodeimmersion['Personne']['dtnai'] = $LocaleHelper->date( 'Date::short', Set::classicExtract( $periodeimmersion, 'Personne.dtnai' ) );

			$periodeimmersion['Adresse']['typevoie'] = Set::enum( Set::classicExtract( $periodeimmersion, 'Adresse.typevoie' ), $typevoie );
		}

		/**
		 *
		 */
		public function delete( $id ) {
			$this->Default->delete( $id );
		}

		/**
		 *
		 */
		public function view( $id ) {
			$this->_setOptions();
			$this->Default->view( $id );
		}

	}
?>
