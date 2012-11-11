<?php    
    /**
     * Code source de la classe Suspensionscuis66Controller.
     *
     * PHP 5.3
     *
     * @package app.Controller
     * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
     */

    /**
     * La classe Suspensionscuis66Controller ...
     *
     * @package app.Controller
     */
    class Suspensionscuis66Controller extends AppController
    {
        public $name = 'Suspensionscuis66';

        public $uses = array( 'Suspensioncui66', 'Option' );

        public $helpers = array( 'Default2', 'Default' );
        public $components = array( 'Jetons2', 'Default' );

        protected function _setOptions() {
			$options = $this->Suspensioncui66->enums();

			$this->set( 'qual', $this->Option->qual() );
			$options = Set::merge(
				$this->Suspensioncui66->Cui->enums(),
				$options
			);
			$this->set( 'options', $options );
		}

		/**
		*
		*/

		public function index( $cui_id = null ) {
			$nbrCuis = $this->Suspensioncui66->Cui->find( 'count', array( 'conditions' => array( 'Cui.id' => $cui_id ), 'recursive' => -1 ) );
			$this->assert( ( $nbrCuis == 1 ), 'invalidParameter' );

			$cui = $this->Suspensioncui66->Cui->find(
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

			$suspensionscuis66 = $this->Suspensioncui66->find(
				'all',
				array(
					'conditions' => array(
						'Suspensioncui66.cui_id' => $cui_id
					),
					'recursive' => -1,
					'contain' => false
				)
			);

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->set( 'cui_id', $cui_id );
			$this->set( compact( 'suspensionscuis66' ) );
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );

			// Retour à la liste des CUI en cas de retour
			if( isset( $this->request->data['Cancel'] ) ) {
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

			if( $this->action == 'add' ) {
				$cui_id = $id;
			}
			else if( $this->action == 'edit' ) {
				$suspensioncui66_id = $id;
				$suspensioncui66 = $this->Suspensioncui66->find(
					'first',
					array(
						'conditions' => array(
							'Suspensioncui66.id' => $suspensioncui66_id
						),
						'contain' => false,
						'recursive' => -1
					)
				);
				$this->set( 'decisioncui66', $suspensioncui66 );

				$cui_id = Set::classicExtract( $suspensioncui66, 'Suspensioncui66.cui_id' );
			}


			// CUI en lien avec la proposition
			$cui = $this->Suspensioncui66->Cui->find(
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

            $dossier_id = $this->Suspensioncui66->Cui->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

            $this->Jetons2->get( $dossier_id );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
                $this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'controller' => 'suspensionscuis66', 'action' => 'index', $cui_id ) );
			}


			if ( !empty( $this->request->data ) ) {
                $this->Suspensioncui66->begin();
				if( $this->Suspensioncui66->saveAll( $this->request->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					$saved = $this->Suspensioncui66->save( $this->request->data );

					if( $saved ) {
						$this->Suspensioncui66->commit();
                        $this->Jetons2->release( $dossier_id );
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'suspensionscuis66', 'action' => 'index', $cui_id ) );
					}
					else {
						$this->Suspensioncui66->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}

				}
			}
			else{
				if( $this-> action == 'edit' ){
					$this->request->data = $suspensioncui66;
				}
			}

			$this->_setOptions();
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );
			$this->render( 'add_edit' );
        }

		/**
		*
		*/

		public function delete( $id ) {
			$this->Default->delete( $id );
		}
    }
?>
