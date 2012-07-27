<?php
    class Accompagnementscuis66Controller extends AppController
    {
        public $name = 'Accompagnementscuis66';
        
        public $uses = array( 'Accompagnementcui66', 'Option' );
        
        public $helpers = array( 'Default2', 'Default' );
        
        protected function _setOptions() {
			$options = $this->Accompagnementcui66->enums();

			$this->set( 'qual', $this->Option->qual() );
			$options = Set::merge(
				$this->Accompagnementcui66->Cui->enums(),
				$options
			);
			$this->set( 'options', $options );
		}

		/**
		*
		*/

		public function index( $cui_id = null ) {
			$nbrCuis = $this->Accompagnementcui66->Cui->find( 'count', array( 'conditions' => array( 'Cui.id' => $cui_id ), 'recursive' => -1 ) );
			$this->assert( ( $nbrCuis == 1 ), 'invalidParameter' );

			$cui = $this->Accompagnementcui66->Cui->find(
				'first',
				array(
					'conditions' => array(
						'Cui.id' => $cui_id
					),
					'contain' => false,
					'recursive' => -1
				)
			);
			$personne_id = Set::classicExtract( $cui, 'Cui.personne_id' );

			$accompagnementscuis66 = $this->Accompagnementcui66->find(
				'all',
				array(
					'conditions' => array(
						'Accompagnementcui66.cui_id' => $cui_id
					),
					'recursive' => -1,
					'contain' => false
				)
			);

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->set( 'cui_id', $cui_id );
			$this->set( compact( 'accompagnementscuis66' ) );
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );

			// Retour à la liste des CUI en cas de retour
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'cuis', 'action' => 'index', $personne_id ) );
			}
		}
		
		
		/** ********************************************************************
		*
		*** *******************************************************************/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}


		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/** ********************************************************************
		*
		*** *******************************************************************/

		protected function _add_edit( $id = null ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$this->Accompagnementcui66->begin();

			if( $this->action == 'add' ) {
				$cui_id = $id;
			}
			else if( $this->action == 'edit' ) {
				$accompagnementcui66_id = $id;
				$accompagnementcui66 = $this->Accompagnementcui66->find(
					'first',
					array(
						'conditions' => array(
							'Accompagnementcui66.id' => $accompagnementcui66_id
						),
						'contain' => false,
						'recursive' => -1
					)
				);
				$this->set( 'decisioncui66', $accompagnementcui66 );
				
				$cui_id = Set::classicExtract( $accompagnementcui66, 'Accompagnementcui66.cui_id' );
			}
			
						
			// CUI en lien avec la proposition
			$cui = $this->Accompagnementcui66->Cui->find(
				'first',
				array(
					'conditions' => array(
						'Cui.id' => $cui_id
					),
					'contain' => false,
					'recursive' => -1
				)
			);


			$personne_id = Set::classicExtract( $cui, 'Cui.personne_id' );
			
			$this->set( 'personne_id', $personne_id );
			$this->set( 'cui', $cui );
			$this->set( 'cui_id', $cui_id );

			
			// On récupère l'utilisateur connecté et qui exécute l'action
			$userConnected = $this->Session->read( 'Auth.User.id' );
			$this->set( compact( 'userConnected' ) );

			
			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'accompagnementscuis66', 'action' => 'index', $cui_id ) );
			}
			
			$dossier_id = $this->Accompagnementcui66->Cui->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			// On récupère l'utilisateur connecté et qui exécute l'action
			$userConnected = $this->Session->read( 'Auth.User.id' );
			$this->set( compact( 'userConnected' ) );
			
			
			if ( !$this->Jetons->check( $dossier_id ) ) {
				$this->Accompagnementcui66->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );
			
			if ( !empty( $this->data ) ) {

				if( $this->Accompagnementcui66->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					$saved = $this->Accompagnementcui66->save( $this->data );

					if( $saved ) {
						$this->Jetons->release( $dossier_id );
						$this->Accompagnementcui66->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'accompagnementscuis66', 'action' => 'index', $cui_id ) );
					}
					else {
						$this->Accompagnementcui66->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}

				}
			}
			else{
				if( $this-> action == 'edit' ){
					$this->data = $accompagnementcui66;
				}
			}
			
			$this->_setOptions();
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );
			$this->render( $this->action, null, 'add_edit' );
        }
        
		/**
		*
		*/

		public function delete( $id ) {
			$this->Default->delete( $id );
		}
    }
?>
