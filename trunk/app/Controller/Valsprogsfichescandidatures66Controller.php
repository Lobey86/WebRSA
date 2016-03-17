<?php
	/**
	 * Code source de la classe Valsprogsfichescandidatures66Controller.
	 *
	 * @package app.Controller
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Controller.php.
	 */
	App::uses('AppController', 'Controller');

	/**
	 * La classe Valsprogsfichescandidatures66Controller ...
	 *
	 * @package app.Controller
	 */
	class Valsprogsfichescandidatures66Controller extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Valsprogsfichescandidatures66';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'Default'
		);

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Xform', 
			'Default', 
			'Default2', 
			'Theme'
		);

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array(
			'Valprogfichecandidature66'
		);

		/**
		 * Listing du contenu de la table
		 */
		public function index() {
			$this->Valprogfichecandidature66->Behaviors->attach( 'Occurences' );
  
            $querydata = $this->Valprogfichecandidature66->qdOccurencesExists(
                array(
                    'fields' => $this->Valprogfichecandidature66->fields(),
                    'order' => array( 'Valprogfichecandidature66.name ASC' )
                )
            );

            $this->paginate = $querydata;
            $requestgroups = $this->paginate('Valprogfichecandidature66');
			$options = $this->_options();
            $this->set( compact('requestgroups', 'options'));
		}

		/**
		 * Ajout d'une entrée
		 */
		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, 'edit' ), $args );
		}

		/**
		 * Modification d'une entrée
		 * 
		 * @param integer $id
		 */
		public function edit( $id = null ) {
            // Retour à la liste en cas d'annulation
            if( isset( $this->request->data['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'index' ) );
            }

			if( !empty( $this->request->data ) ) {
				$this->Valprogfichecandidature66->create( $this->request->data );
				$success = $this->Valprogfichecandidature66->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $this->Valprogfichecandidature66->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Valprogfichecandidature66.id' => $id )
					)
				);
				$this->assert( !empty( $this->request->data ), 'error404' );
			}
			else{
				$this->request->data['Valprogfichecandidature66']['actif'] = true;
			}
			
			$options = $this->_options();
			
			$this->set( compact( 'options' ) );

			$this->view = 'edit';
		}

		/**
		 * Suppression d'une entrée
		 * 
		 * @param integer $id
		 */
		public function delete( $id ) {
			$this->Default->delete( $id );
		}

		/**
		 * Visualisation de la table
		 * 
		 * @param integer $id
		 */
		public function view( $id ) {
			$this->Default->view( $id );
		}
		
		/**
		 * Options pour la vue
		 * 
		 * @return array
		 */
		protected function _options() {
			$options['Valprogfichecandidature66']['progfichecandidature66_id'] = $this->Valprogfichecandidature66->Progfichecandidature66->find('list');
			
			return $options;
		}
		
	}
?>